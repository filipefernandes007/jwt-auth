var Api = new Vue({
  el: '#vueJwt',
  data: {
    vueJwtResult: '',
    vueJwtError: ''
  },
  methods: {
    getUser: function (event) {
        var _this = this;
        fetch('/api/user/1', {
            headers: {'Authorization':'Bearer ' + AppGetJWT.jwt},
        })
        .then(response => {
          if (!response.ok) {
            var errorMsg = `Request rejected with status ${response.status}`;
            $(_this.$refs.jwt).hide();
            $(_this.$refs.error).show();
            _this.vueJwtError = errorMsg;

            throw Error(errorMsg);    
          }

          return response.json();
        })
        .then(json => {
            $(_this.$refs.error).hide();
            $(_this.$refs.jwt).show();
            _this.vueJwtResult = json;
        })
        .catch(console.error);
    }
  }
});