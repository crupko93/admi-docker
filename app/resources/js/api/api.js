import axios         from '../plugins/axios';
import announcements from './endpoints/announcements';
import auth          from './endpoints/auth';
import notifications from './endpoints/notifications';
import permissions   from './endpoints/permissions';
import roles         from './endpoints/roles';
import users         from './endpoints/users';

export const API = {
    announcements: announcements(axios),
    auth         : auth(axios),
    notifications: notifications(axios),
    permissions  : permissions(axios),
    roles        : roles(axios),
    users        : users(axios)
};
