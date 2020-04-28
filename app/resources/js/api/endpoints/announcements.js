export default remote => ({
    all: (pagination = {}) => remote.get('announcements', { params: pagination }),
    get: id => remote.get(`announcements/${id}`),

    markAnnouncementsAsRead: () => remote.patch('announcements/read-announcement'),

    save: data => {
        return data.id
            ? remote.put('announcements', data)
            : remote.post('announcements', data);
    },

    delete: ids => {
        ids = Array.isArray(ids) ? _.join(ids) : ids;
        return remote.delete(`announcements/${ids}`);
    }
});
