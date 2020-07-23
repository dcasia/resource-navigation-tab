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

            if (!this.$route.query.navigationTab) {

                const slug = this.card.resources[ 0 ].slug

                if (slug) {

                    this.$router.replace({
                        query: {
                            ...this.$route.query, navigationTab: slug
                        }
                    })

                }

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
