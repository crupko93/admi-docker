export default remote => ({
    ////////////
    // Admins //
    ////////////
    all     : (pagination = {}) => remote.get('permissions', {params: pagination}),
    get     : id => remote.get(`permissions/${id}`),
    create  : data => remote.post('permissions', data),
    update  : data => remote.put('permissions', data),
    delete  : ids => {
        ids = Array.isArray(ids) ? _.join(ids) : ids;
        return remote.delete(`permissions/${ids}`);
    },
    list    : () => remote.get('permissions/list')
});
