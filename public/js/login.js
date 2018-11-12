var AppGetJWT = new Vue({
  el: '#login',
  data: {
    jwt: '',
    username: 'filipefernandes007',
    pwd: '123',
  },
  methods: {
    login: function(event) {
        var _this    = this;
        var jsonData = {"username": this.username, "pwd": this.pwd};
        fetch('/api/auth', {
            method: 'post',
            headers: {'Content-Type':'application/json; charset=utf-8'},
            body: JSON.stringify(jsonData)
        }).then(function(response) {
            return response.json();
        }).then(function(data) {
            _this.jwt = data.jwt;
        });
    }
  }
});