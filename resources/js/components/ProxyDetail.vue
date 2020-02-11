<script>

    import DetailComponent from '~~nova~~/views/Detail.vue'
    import { Minimum } from 'laravel-nova'

    export default {
        extends: DetailComponent,
        data() {
            return {
                activeTab: this.$route.query.navigationTab
            }
        },
        watch: {
            '$route.query.navigationTab'(activeTab) {
                this.activeTab = activeTab
            }
        },
        computed: {
            extraCardParams() {
                return {
                    navigationTab: this.activeTab,
                    resourceId: this.resourceId
                }
            }
        },
        methods: {
            /**
             * Get the resource information.
             */
            getResource() {

                this.resource = null

                let query = ''

                if (this.activeTab) {

                    query = '?navigationTab=' + this.activeTab

                }

                return Minimum(
                    Nova.request().get(
                        '/nova-api/' + this.resourceName + '/' + this.resourceId + query
                    )
                )
                    .then(({ data: { panels, resource } }) => {
                        this.panels = panels
                        this.resource = resource
                        this.loading = false
                    })
                    .catch(error => {
                        if (error.response.status >= 500) {
                            Nova.$emit('error', error.response.data.message)
                            return
                        }

                        if (error.response.status === 404 && this.initialLoading) {
                            this.$router.push({ name: '404' })
                            return
                        }

                        if (error.response.status === 403) {
                            this.$router.push({ name: '403' })
                            return
                        }

                        Nova.error(this.__('This resource no longer exists'))

                        this.$router.push({
                            name: 'index',
                            params: { resourceName: this.resourceName }
                        })
                    })
            }
        }
    }

</script>
