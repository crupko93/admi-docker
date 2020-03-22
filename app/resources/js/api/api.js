import axios       from '../plugins/axios';
import auth        from './endpoints/auth';
import users       from './endpoints/users';
import permissions from './endpoints/permissions';
import roles       from './endpoints/roles';

export const API = {
    auth       : auth(axios),
    permissions: permissions(axios),
    roles      : roles(axios),
    users      : users(axios)
};
