import axios from 'axios';
import authHeader from './auth-header';


class ApptService {

  apiUrl = 'http://develop.com/encore-med/api/v1/appointment/';


  postRequest(method,params)
  {
    return axios.post(this.apiUrl+method, params, { headers: authHeader() });
  }

  getRequest(method)
  {
    return axios.get(this.apiUrl + method, { headers: authHeader() });
  }

  list() {
    return this.getRequest('list');
  }

  create(params)
  {

    return this.postRequest('create',params);
  }

  schedule(params)
  {
    return this.postRequest('schedule',params);
  }

  arrive(params)
  {
    return this.postRequest('arrive',params);
  }

}

export default new ApptService();