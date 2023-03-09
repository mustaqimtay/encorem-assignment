import axios from 'axios';
import Swal  from './swal'

const API_URL = 'http://develop.com/encore-med/api/v1/';

class AuthService {

  login(user) {
    return axios
      .post(API_URL + 'user/login', {
        username: user.username,
        password: user.password
      })
      .then(response => {

        if (response.data.response !==undefined && response.data.response.status===200) {
          localStorage.setItem('user', JSON.stringify(response.data));
          return response.data;
        }
        else
        {
          Swal.alert(response.data);
          return false;
        }
        
      },error => {
        console.log(error);
      });
  }

  logout() {
    localStorage.removeItem('user');
  }

}

export default new AuthService();