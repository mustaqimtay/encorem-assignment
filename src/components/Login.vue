<template>
    <div class="col-md-12">
      
      <div class="card card-container">
        <center><img src="https://s3-ap-southeast-1.amazonaws.com/encorehealthcare/website/image/logo.png" style="width: 300px;padding-top:20px" alt="logo"></center>
        <Form @submit="handleLogin" :validation-schema="schema">
          <div class="form-group">
            <label for="username">Username</label>
            <Field name="username" type="text" class="form-control" />
            <ErrorMessage name="username" class="error-feedback" />
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <Field name="password" type="password" class="form-control" />
            <ErrorMessage name="password" class="error-feedback" />
          </div>
  
          <div class="form-group">
            <button class="btn btn-primary btn-block" :disabled="loading">
              <span v-show="loading" class="spinner-border spinner-border-sm"></span>
              <span>Login</span>
            </button>
          </div>
  
          <div class="form-group">
            <div v-if="message" class="alert alert-danger" role="alert">
              {{ message }}
            </div>
          </div>
        </Form>
      </div>
    </div>
  </template>

  <script>
  import { Form, Field, ErrorMessage } from "vee-validate";
  import * as yup from "yup";
  
  export default {
    name: "Login",
    components: {
      Form,
      Field,
      ErrorMessage,
    },
    data() {
      const schema = yup.object().shape({
        username: yup.string().required("Username is required!"),
        password: yup.string().required("Password is required!"),
      });
  
      return {
        loading: false,
        message: "",
        schema,
      };
    },
    computed: {
      loggedIn() {
        return this.$store.state.auth.status.loggedIn;
      },
    },
    created() {
      if (this.loggedIn) {
        this.$router.push("/appointment");
      }
    },
    methods: {
      handleLogin(user) {
        this.loading = true;
        
        this.$store.dispatch("auth/login", user).then(
          () => {
            this.$router.push("/appointment");
          },
          (error) => {
          
            this.loading = false;
            this.message =
              (error.response &&
                error.response.data &&
                error.response.data.message) ||
              error.message ||
              error.toString();
          }
        );

       
      },
    },
  };
  </script>