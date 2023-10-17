<template>

    <Card class="resource-navigation-card overflow-x-hidden whitespace-nowrap overflow-x-auto flex flex-row items-center md:justify-center">

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

    import { interceptors } from './RequestHighjacker'

    interceptors.push(config => {

        try {

            if (config.params === undefined || config.params === null) {
                config.params = {}
            }

            const searchParams = new URLSearchParams(window.location.search)

            if (searchParams.has('x-tab')) {
                config.params[ 'x-resource-navigation-tab' ] = searchParams.get('x-tab')
            }

        } catch (error) {

            console.log('ResourceNavigationCard.vue error:', error)

        }

        return config

    })

    export default {
        props: [ 'card' ],
        setup(props) {
            return {
                onNavigate: slug => {

                    const searchParams = new URLSearchParams(window.location.search)
                    const url = window.location.pathname.replace(new RegExp(`^${ Nova.config('base') }`), '')
                    const activeTab = props.card.resources.find(({ isActive }) => isActive)

                    if (activeTab.slug !== slug) {

                        Nova.visit(url, {
                            data: {
                                ...Object.fromEntries(searchParams.entries()),
                                'x-tab': slug,
                            },
                        })

                    }

                },
            }
        },
    }

</script>

<style scoped>

    .resource-navigation-card {
        min-height: auto;
    }

</style>
