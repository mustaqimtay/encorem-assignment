import jwt_decode from 'jwt-decode';

export default function authHeader() {
    let user = JSON.parse(localStorage.getItem('user'));

    if (user && user.response.result !== undefined) {
        const token = user.response.result.token;
        if (token) {
            const decoded = jwt_decode(token);
            if(decoded.exp > Date.now() / (60*60))
                return { Authorization: 'Bearer ' + user.response.result.token };
        } 
        return {};
    } else {
        return {};
    }
}