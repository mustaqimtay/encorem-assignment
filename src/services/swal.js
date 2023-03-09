import VueSweetalert2 from 'sweetalert2';

class Swal
{
    alert(resp,messg='')
    {
     
      let title;
      let message;
      let icon = 'success';

      if(resp.error !== undefined)
      {
        title = 'Error';
        icon = 'warning';
        message = resp.error.message;

      }else if(resp.response !==undefined && resp.response.status !== 200)
      {
        title = 'Error';
        icon = 'warning';
        message = resp.response.result.message;
      }
      else
      {
        if(messg!=='') resp.result = messg;
        title = 'Operation Success';
        message = resp.response.result.message;
        
      }
      // Use sweetalert2
      VueSweetalert2.fire({
        title: title,
        text: message,
        icon: icon,
      });
    }
}

export default new Swal();