<template>

    <Card class="resource-navigation-card whitespace-nowrap overflow-x-auto flex flex-row items-center md:justify-center">

        <div @click="onNavigate(resource.slug)"
             v-for="(resource, key) of card.resources"
             class="p-4 pt-6 flex-1 text-center cursor-pointer leading-tight text-sm transition"
             :class="[
                 { 'border-b-2 hover:border-[rgba(var(--colors-primary-500))] first:rounded-l-lg last:rounded-r-lg': true },
                 { 'border-[rgba(var(--colors-primary-500))] font-bold': resource.isActive === true },
                 { 'border-transparent': resource.isActive === false },
             ]">

            {{ resource.name }}

        </div>

    </Card>

</template>

<script>

    const novaRequest = Nova.request

    const interceptors = []
    const interceptorsInstance = []

    Nova.request = (...params) => {

        for (const param of params) {

            for (const interceptor of interceptors) {
                interceptor(param)
            }

        }

        const axiosInstance = novaRequest(...params)

        if (axiosInstance instanceof Promise) {
            return axiosInstance
        }

        for (const interceptor of interceptors) {

            interceptorsInstance.push({
                instance: axiosInstance,
                interceptor: axiosInstance.interceptors.request.use(config => interceptor(config)),
            })

        }

        return axiosInstance

    }

    interceptors.push(config => {

        if (config.params === undefined) {
            config.params = {}
        }

        const searchParams = new URLSearchParams(window.location.search)

        if (searchParams.has('x-tab')) {
            config.params[ 'x-resource-navigation-tab' ] = searchParams.get('x-tab')
        }

        return config

    })

    export default {
        props: [ 'card' ],
        setup() {
            return {
                onNavigate: slug => {

                    const searchParams = new URLSearchParams(window.location.search)

                    Nova.visit(window.location.pathname, {
                        data: {
                            ...Object.fromEntries(searchParams.entries()),
                            'x-tab': slug,
                        }
                    })

                }
            }
        }
    }

</script>

<style scoped>

    .resource-navigation-card {
        min-height: auto;
    }

</style>
