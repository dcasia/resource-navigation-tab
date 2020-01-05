import ProxyDetail from './components/ProxyDetail'
import ResourceNavigationCard from './components/ResourceNavigationCard'

Nova.booting((Vue, router, store) => {

    router.beforeEach((from, to, next) => {

        if (from.name === 'detail') {

            return next({ ...from, name: 'detail-navigation-card' })

        }

        next()

    })

    router.addRoutes([
        {
            name: 'detail-navigation-card',
            path: '/resources/:resourceName/:resourceId',
            component: ProxyDetail,
            props: true
        }
    ])

    Vue.component('resource-navigation-card', ResourceNavigationCard)
})
