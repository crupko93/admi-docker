import axios from '../plugins/axios';
import auth from './endpoints/auth';
import users from './endpoints/users';

export const API = {
    auth : auth(axios),
    users: users(axios)
};
