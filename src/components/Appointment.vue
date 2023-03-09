<template>
  <div>
  <div class="container-fluid">
  
    <h1>Manage Appointments</h1>
    <div  class="d-flex justify-content-between align-items-center">
      <p></p>
      <button type="button" class="btn btn-info" @click="openNewApptModal">Add</button>
    </div>

    <div  class="row mt-4"> 
      <div class="col-xl-12">
        <h4>List of Appointments</h4>
        <div class="table-responsive-xl">
          <table class="table">
            <thead>
              <tr>
                <th width="17%">Created</th>
                <th width="17%">Appt On</th>
                <th width="17%">Arrive On</th>
                <th width="24%">Patient Info</th>
                <th width="10%">Status</th>
                
                <th width="15%">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr><td colspan="7"><div class="alert alert-danger" v-if="appts.length < 1">No data is found!</div></td></tr>
              <tr v-for="appt in appts" :key="appt.id">
                <td><pre>{{ formatDate(appt.created_at) }}</pre></td>
                <td><pre>{{ formatDate(appt.appt_datetime) }}</pre></td>   
                <td><pre>{{ formatDate(appt.arrived_at) }}</pre></td>
                <td><pre>{{ jsonParse(appt.patient,'name')+'\t\n'+jsonParse(appt.patient,'ic')+'\t\n'+jsonParse(appt.patient,'mobileNo')+'\n'+jsonParse(appt.patient,'mrn') }}</pre></td>
                <td><pre>{{ appt.status }}</pre></td>
            
                <td>
                  <button type="button" class="btn btn-success btn-sm" @click="openApptModal(appt.id)" v-if="appt.status== 'pending'"><font-awesome-icon icon="check" /></button>&nbsp;
                  <button type="button" class="btn btn-warning btn-sm" @click="openRApptModal(appt.id)" v-if="appt.status== 'pending'"><font-awesome-icon icon="calendar" /></button>
                  <!-- <button type="button" class="btn btn-sm btn-secondary mr-2" @click="editUser(appt)" >Edit</button>
                  <button type ="button" class="btn btn-sm btn-danger" @click="deleteUser(appt)">Delete</button> -->
                </td>
              </tr>
            </tbody>    
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="arriveModal" ref="arriveModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Update Patient Arrival</h5>
          <button type="button" class="btn-close" @click="closeApptModal" ></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="form-group">
              <label for="mrn">Arrived On:</label>
              <Datepicker  v-model="apptArrive.appointment_arrive_at" :config="config"></Datepicker>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeApptModal">Close</button>
          <button type="button" class="btn btn-primary" @click="updateArrived">Save Changes</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="rescheduleModal" ref="rescheduleModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Reschedule Appointment</h5>
          <button type="button" class="btn-close" @click="closeRApptModal" ></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="form-group">
              <label for="mrn">Appointment On:</label>
              <Datepicker  v-model="apptReschedule.appointment_datetime" :config="config"></Datepicker>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeRApptModal">Close</button>
          <button type="button" class="btn btn-primary" @click="rescheduleAppt">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="addApptModal" ref="addApptModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Add Appointment</h5>
          <button type="button" class="btn-close" @click="closeNewApptModal" ></button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="submitForm">
            <div class="form-group">
              <label for="name">Name:</label>
              <input type="text" class="form-control" id="name" v-model="appt.patient_name" required>
            </div>
            <div class="form-group">
              <label for="ic">IC:</label>
              <input type="text" class="form-control" id="ic" v-model="appt.patient_ic" required>
            </div>
            <div class="form-group">
              <label for="mobileNo">Mobile No:</label>
              <input type="text" class="form-control" id="mrn" v-model="appt.patient_mobileNo" required minlength="6" >
            </div>
            <div class="form-group">
              <label for="mrn">MRN:</label>
              <input type="text" class="form-control" id="mrn" v-model="appt.patient_mrn" minlength="6" >
            </div>
            <div class="form-group">
              <label for="mrn">Appointment On:</label>
              <Datepicker  v-model="appt.appointment_datetime" :config="config"></Datepicker>
            </div>
            <div class="form-group">
              <label for="mrn">Status</label>
              <select name="status" class="form-control" v-model="appt.status">
                <option value="pending" selected>Pending</option>
                <option value="arrived">Arrived</option>
                <option value="rescheduled">Rescheduled</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeNewApptModal">Close</button>
          <button type="button" class="btn btn-primary" @click="newAppt">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

</div>
</template>
<script>

import * as bootstrap from 'bootstrap/dist/js/bootstrap';
import Swal from '../services/swal';
import ApptService from '../services/appt.service';


import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import moment from 'moment';

import { ref } from 'vue';
export default {
  setup() {
    const apptDate = ref(new Date());
    const config = {
      format: 'yyyy-MM-dd HH:mm',
      wrapperClass: 'datepicker',
      inputClass: 'form-control',
    };

    return {
      apptDate,
      config,
    };
  },
  data() {
    return {
      appt: {
       patient_name: '',
       patient_ic: '', 
       patient_mrn: '', 
       patient_mobileNo: '',
       status : '',
       appointment_datetime : this.apptDate
      },
      appts: [],
      apptArrive :{
        appointment_id : null,
        appointment_arrive_at : this.apptDate,
      },
      apptReschedule :{
        appointment_id : null,
        appointment_datetime : this.apptDate,
      },
      selectedAppt: null,
      rModal : null,
      arriveModal : null,
      newApptModal : null
    };
  },
  components: {
    Datepicker,
    
  },

  async mounted() {
    await this.loadAppt();
    this.arriveModal = new bootstrap.Modal(this.$refs.arriveModal);
    this.rModal = new bootstrap.Modal(this.$refs.rescheduleModal);
    this.newApptModal = new bootstrap.Modal(this.$refs.addApptModal);

  },
  methods: {
    async loadAppt() {
      try {
        const response = await ApptService.list();
        if(response.data.response !== undefined)
        {
          this.appts = JSON.parse(response.data.response.result);
        }else{
           Swal.alert(response.data);
        }
      } catch (error) {
        console.error(error);
      }
    },
   
    async updateArrived() {
      try {

        const response = await ApptService.arrive(this.apptArrive);
        this.arriveModal.hide();
      
        Swal.alert(response.data);
        await this.loadAppt();
      } catch (error) {
        console.error(error);
      }
    },
    async rescheduleAppt() {
      try {
        const response = await ApptService.schedule(this.apptReschedule);
        this.rModal.hide();
         Swal.alert(response.data);
        await this.loadAppt();
      } catch (error) {
        console.error(error);
      }
    },

    async newAppt() {
      try {
        const response = await ApptService.create(this.appt);
        this.newApptModal.hide();
        Swal.alert(response.data);
        await this.loadAppt();
      } catch (error) {
        console.error(error);
      }
    },
    editUser(appt) {
      var patient = JSON.parse(appt.patient);
      this.appt = { ...appt };
      this.appt['patient_name'] = patient.name;
      this.appt['patient_ic'] = patient.ic;
      this.appt['patient_mrn'] = patient.mrn;
      this.appt['patient_mobileNo'] = patient.mobileNo;
      this.appt['appointment_datetime'] = appt.appt_datetime;
      this.selectedAppt = appt;
    },
    openApptModal(id)
    {
      this.apptArrive.appointment_id = id;
      this.arriveModal.show();
    },
    closeApptModal()
    {
      this.arriveModal.hide();
    },
    openRApptModal(id)
    {
      this.apptReschedule.appointment_id = id;
      this.rModal.show();
    },
    closeRApptModal()
    {
      this.rModal.hide();
    },
    openNewApptModal()
    {
      this.newApptModal.show();
    },
    closeNewApptModal()
    {
      this.newApptModal.hide();
    },
    

    // async deleteUser(appt) {
      
    // try {
    //   await ApptService.delete();
    //   await this.loadAppt();
    // } catch (error) {
    //   console.error(error);
    // }
      
    // },
    // async submitForm() {
    //   try {
    //     if (this.selectedAppt) {
    //       await axios.put(this.apiUrl+'appointment/update', this.user, { headers: this.headers });
    //       this.selectedAppt = null;
    //     } else {
    
    //       await axios.post(this.apiUrl+'appointment/create', this.appt, { headers: this.headers });
      
    //     }
    //     await this.loadAppt();
    //   } catch (error) {
    //     console.error(error);
    //   }
    // },

    jsonParse(string,field)
    {
      if(string===undefined) return "";
      var obj = JSON.parse(string);

      return obj[field];
    },

    formatDate(date)
    {
      if(date==null) return "";
      return moment(date).format('MMMM Do YYYY, h:mma');
    }
  },
};
</script>