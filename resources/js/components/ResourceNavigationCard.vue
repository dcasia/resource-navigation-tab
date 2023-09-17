<template>

    <Card class="resource-navigation-card whitespace-nowrap pt-2 overflow-x-auto flex flex-row items-center md:justify-center">

        <div @click="onNavigate(resource.slug)"
             v-for="(resource, key) of card.resources"
             class="p-6 flex-1 text-center cursor-pointer leading-tight text-sm transition"
             :class="[
                 { 'border-b-2 hover:border-[rgba(var(--colors-primary-500))] first:rounded-l-lg last:rounded-r-lg': true },
                 { 'border-[rgba(var(--colors-primary-500))] font-bold': resource.isActive === true },
                 { 'border-transparent': resource.isActive === false },
             ]">

            {{ resource.name }}

        </div>

    </Card>

</template>

<script setup>

    const props = defineProps([ 'card' ])
    const activeTab = Object.values(props.card.resources).find(resource => resource.isActive)

    if (activeTab) {
        setCookie(props.card.cookieName, activeTab.slug)
    }

    function onNavigate(slug) {
        setCookie(props.card.cookieName, slug)
        Nova.visit(window.location.pathname)
    }

    function setCookie(name, value, expiration) {
        const date = new Date()
        date.setTime(date.getTime() + (expiration * 24 * 60 * 60 * 1000))
        const expires = 'expires=' + date.toUTCString()
        document.cookie = name + '=' + value + ';' + expires + ';path=/'
    }

</script>

<style scoped>

    .resource-navigation-card {
        min-height: auto;
    }

</style>
