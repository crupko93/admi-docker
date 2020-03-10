export default remote => ({
    me           : () => remote.get('profile'),
    updateProfile: data => remote.put('profile', data)
});
