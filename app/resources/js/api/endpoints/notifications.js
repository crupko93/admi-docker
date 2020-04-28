import store from '~/store/index';

export default remote => ({

    deleteNotification: id => remote.delete(`users/notification/${id}`),

    all: (query = {}) => remote.get('notifications', { params: query }),

    markAsRead: data => remote.patch('notifications/mark-as-read', data),

    remove: id => remote.delete(`notifications/${id}`),

    refresh: () => {
        API.notifications.all()
            .then(r => {
                store.dispatch('notifications/setNotifications', {
                    announcements: r.data.announcements,
                    system       : r.data.system
                });
            })
            .catch(Utils.standardErrorResponse);
    },
});
