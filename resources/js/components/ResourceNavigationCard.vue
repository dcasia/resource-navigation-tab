<template>

    <card class="resource-navigation-card whitespace-no-wrap flex flex-row items-center justify-center">

        <router-link
                class="p-6 dim flex-1 text-center no-underline text-primary border-b-2 cursor-pointer border-transparent hover:border-90"
                v-for="(resource, key) of card.resources"
                replace
                :key="key"
                :to="{
                    query: { ...$route.query, navigationTab: resource.slug },
                    params: { ...$route.params, resourceId: resource.resourceId || $route.params.resourceId }
                }"
                @click.native="onNavigate">

            {{ resource.label }}

        </router-link>

    </card>

</template>

<script>

    function setCookie(name, value, expiration) {
        const date = new Date()
        date.setTime(date.getTime() + (expiration * 24 * 60 * 60 * 1000))
        const expires = 'expires=' + date.toUTCString()
        document.cookie = name + '=' + value + ';' + expires + ';path=/'
    }

    function deleteCookie(name) {
        document.cookie = name + '=; Path=/; Expires=Thu, 01 Jan 1970 00:00:01 GMT;'
    }

    function getCookie(cname, defaultValue) {
        const name = cname + "=";
        const decodedCookie = decodeURIComponent(document.cookie);
        const ca = decodedCookie.split(';');

        for (let i = 0; i <ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) === 0) {
                return c.substring(name.length, c.length);
            }
        }

        return defaultValue;
    }

    export default {
        name: 'NavigationCard',
        props: [
            'card',
            'resource',
            'resourceId',
            'resourceName'
        ],
        mounted() {

            /**
             * Nova sort the cards per size, smaller comes first, so to keep this card above everything,
             * It starts with the smallest possible size, and then change resize itself to the largest
             */
            this.$parent.$el.classList.add('w-full')

            const slug = this.card.resources[0].slug
            const activeTab = getCookie('navigation_tab', '');

            if (!this.$route.query.navigationTab) {
                if (slug) {
                    this.$router.replace({
                        query: {
                            ...this.$route.query, navigationTab: slug
                        }
                    })
                }
            }
            /**
             * If we have a tab active on page load - please load the correct content
             */
            else if (this.$route.query.navigationTab && this.$route.query.navigationTab !== slug) {
               if (activeTab !== '' && activeTab !== this.$route.query.navigationTab) {
                   this.$router.replace({
                       query: {
                          ...this.$route.query, navigationTab: activeTab
                       }
                   })
               }

               this.onNavigate();
            }

        },
        beforeCreate() {

            this.$on('hook:destroyed', () => deleteCookie('navigation_tab'))

        },
        methods: {
            getDetailCard(element = this) {
                if (element.hasOwnProperty('initializeComponent')) {
                    return element
                }

                return this.getDetailCard(element.$parent)

            },
            onNavigate() {
                setCookie('navigation_tab', this.$route.query.navigationTab)

                const detail = this.getDetailCard()
                const activeTab = this.$route.query.navigationTab
                const activeCards = this.card.cardsToRemove[ activeTab ]

                detail.cards = detail.cards.filter(card => !activeCards.includes(card.navigationTabClass))

                detail.initializeComponent()
                detail.fetchCards()
            }
        }
    }

</script>


<style lang="scss" scoped>

    .card-panel {

        height: auto;

    }

    .card {

        a:first-child {
            border-bottom-left-radius: .5rem;
        }

        a:last-child {
            border-bottom-right-radius: .5rem;
        }

        a.router-link-exact-active {
            border-color: var(--primary);
        }

    }

</style>
