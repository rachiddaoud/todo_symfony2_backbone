
var logged = false;
var token = '';
var all_url = 'http://localhost/todo/web/';
var sortBy = 'priority';

(function ($) {

	$( ".radioinput" ).buttonset();

	$.ajaxSetup({
	    statusCode: {
	        401: function(){
	            // Redirec the to the login page.
	            logged = false;
	        	token = '';

	            console.log("Not authorized");
	            document.location.href = "#login";
	        },
	        403: function() {
	            // 403 -- Access denied
	            //window.location.replace('/#denied');
	        }
	    }
	});

	AppRouter = Backbone.Router.extend({
	    routes: {
	        "": "home",
	        //"todo/:id": "todoChange",
	        "login" : "login"
	    },
 		// Constructor
        initialize: function(){
			this.appview = new AppView();
	        //Required for Backbone to start listening to hashchange events
	        Backbone.history.start();

        },
	    login: function() {
	    	new LoginView();
	    },
	    todoChange:function(id){
	    	new ChangeTodoView({model:this.appview.collection.get(id)});
	    },
	    home:function(){
	    	token = $.cookie('_token');
	    	if(!token){
	    		document.location.href = "#login";
	    	}else{
  				this.appview.fetch();
	    	}
	    }
	});

	//TODO MODEL
	var TodoModel = Backbone.Model.extend({
		defaults:{
			title:  "",
			duedate:  "",
			priority:  "",
			completed:  ""
		},
		url: function() {
			if(this.id)
		  		return all_url  + 'todo/' + this.id +'?token='+token;
		 	else
		  		return all_url  + 'todo/'  +'?token='+token;
		}
	});

	var TodoCollection = Backbone.Collection.extend({
		model: TodoModel,
		url: all_url+'todo/',
		orderBy:function(){return sortBy;},
		comparator: function(model){
			if(this.orderBy == 'date')
			return (new Date(model.get('duedate'))).getTime();
			else{
				return model.get('priority');
			}
		},
		//This is our Friends collection and holds our Friend models
		initialize: function (models, options) {
			this.view = options.view;
			//Listen for new additions to the collection and call a view function if so
			this.bind("sort",this.view.addTodos,this.view);
		}
	});
	//Views
	//App view
	AppView = Backbone.View.extend({
		el: "#view",
		template: _.template($("#template-app").html()),
	    render: function() {
	    	var $el = $(this.el);
			$el.html(this.template());
			return this;
	    },
	    fetch:function(){
			this.collection.fetch({ data: { token:token} });
	    },
		initialize: function () {
			this.render();
			this.collection = new TodoCollection( null, { view: this });
		},
		events: {
        	'keypress input[type=text]'	: 'submitTodo',
        	'click button'				: 'submitTodo',
        	'click #sortByPriority' 	: 'sortByPriority',
        	'click #sortByDate' 		: 'sortByDate'
		},
		submitTodo: function(e) {
	        if (e.keyCode != 13 && !$(e.currentTarget).is("button")) return;
	        var view = this;
	        var todo_title = $('input[type=text]',this.el);

	        var todo = new TodoModel({title:todo_title.val()});
			todo.save(null,{
				success: function(model, response, options){
					todo_title.val("");
					view.collection.add(model,{sort: false});
					if(sortBy =='date'){
						view.addTodoByDate(model);
					}else{
						view.addTodoByPriority(model);
					}
					console.log("Saving todo model with success");
				},
				error : function(model, xhr, options){
					console.log("error");
					alert("error while saving the todo model");
				}
			});
      	},
		sortByDate: function(event){
			if(sortBy == 'date')
				return true;
			$el = $(this.el);
			this.trigger('reset');
			$el.find("#todolist-1").html('');
			$el.find("#todolist-2").html('');
			$el.find("#todolist-3").html('');
			sortBy = 'date';
			this.collection.sort();
		},
		sortByPriority: function(event){
			if(sortBy == 'priority')
				return true;
			this.trigger('reset');
			$el = $(this.el);
			$el.find("#todolist-1").html('');
			$el.find("#todolist-2").html('');
			$el.find("#todolist-3").html('');
			sortBy = 'priority';
			this.collection.sort();
		},
		addTodos: function (collection) {
			var view = this;
			collection.each(function(model) {
				if(sortBy =='date'){
					view.addTodoByDate(model);
				}else{
					view.addTodoByPriority(model);
				}
			});
		},
		addTodoByPriority:function(model){
			var $el = $(this.el);
		    var item = new TodoItemView({ model: model,app:this });
			switch(model.get('priority')){
				case 2:
				if($el.find("#todolist-3 h3").length == 0){
					$el.find("#todolist-3").append('<h3>Priority low</h3>');
				}
		    	$el.find("#todolist-3").append($(item.render().el));
				break;
				case 1:
				if($el.find("#todolist-2 h3").length == 0){
					$el.find("#todolist-2").append('<h3>Priority Medium</h3>');
				}
		    	$el.find("#todolist-2").append($(item.render().el));
				break;
				case 0:
				if($el.find("#todolist-1 h3").length == 0){
					$el.find("#todolist-1").append('<h3>Priority High</h3>');
				}
		    	$el.find("#todolist-1").append($(item.render().el));
				break;
				default:
				break;
			}
		},
		addTodoByDate:function(model){
	    	console.log("add by date");
			var $el = $(this.el);
		    var item = new TodoItemView({ model: model,app:this });


			var today = new Date();
			date = new Date(model.get('duedate'));
			difference = date - today;
			days = Math.round(difference/(1000*60*60*24));



		    //alert(days);
			if(days>0){
				if($el.find("#todolist-3 h3").length == 0){
					$el.find("#todolist-3").append('<h3>Other</h3>');
				}
		    	$el.find("#todolist-3").append($(item.render().el).hide().fadeIn(2000));
			}else if(days==0){
				if($el.find("#todolist-2 h3").length == 0){
					$el.find("#todolist-2").append('<h3>Tomorrow</h3>');
				}
		    	$el.find("#todolist-2").append($(item.render().el).hide().fadeIn(2000));
			}else{
				if($el.find("#todolist-1 h3").length == 0){
					$el.find("#todolist-1").append('<h3>Today</h3>');
				}
		    	$el.find("#todolist-1").append($(item.render().el).hide().fadeIn(2000));
			}
		}
	});

	//TODO view item
	var TodoItemView = Backbone.View.extend({
	    className: 'list-menu-item',
	    template: _.template($("#template-todo-item").html()),

	    events: {
	      'click input[type=checkbox]': 'check',
	      // On double click open the modification form
	      'dblclick': 'update',
	    },

	    initialize: function() {
	      this.model.on('change', this.destroy, this);
	      this.options.app.on('reset',this.destroye,this);
	    },
	    destroye:function(){
	    	this.remove();
	      	this.model.off('change', this.destroy);
	    },
	    destroy:function(){
	    	console.log(this);
	    	this.remove();
	      	this.model.off('change', this.destroy);
	    	if(sortBy =='date'){
				this.options.app.addTodoByDate(this.model);
			}else{
				this.options.app.addTodoByPriority(this.model);
			}
	    },
	    render: function() {
	      var $el = $(this.el);
	      $el.data('listId', this.model.get('id'));
	      $el.html(this.template(this.model.toJSON()));
	      return this;
	    },
	    check:function(){
			this.model.save({completed:!this.model.get('completed')},{wait: true});
	    },
	    update: function() {
	    	var $el = $(this.el);

	    	new ChangeTodoView({model:this.model});
			return false;
	    }
  	});

	//Change TODO screen item
	var ChangeTodoView = Backbone.View.extend({
		tagName: "div",
	    template: _.template($("#template-changetodo-item").html()),
	    events: {
	      'click button.login'	: 'submit',
	      'click'		: 'destroy'
	    },
	    submit:function(e){
	    	var title = this.$el.find('[name="title"]').val();
	    	var duedate = this.$el.find('[name="duedate"]').val();
	    	var priority = this.$el.find('[name="priority"]:checked').val();
	    	if(title == this.model.get('title') && duedate == this.model.get('duedate') && priority == this.model.get('priority')){
	    		this.remove();
	    	}else{
	    		this.model.save({title:title,duedate:duedate,priority:priority},{
	    			wait: true,
	    			error: function(model, jqXHR, options){
						console.log("error while updating the todo model "+JSON.stringify(jqXHR.responseJSON));
						if(jqXHR.responseJSON.code){
							$("#login-form > .error").html(jqXHR.responseJSON.message);
							$("#login-form > .error").show();
						}
				}});
	    	}
	    },
	    destroye:function(e){this.remove();},
	    destroy:function(e){
	    	if($(e.target).hasClass('biglayer'))
	    	this.remove();
	    },
	    initialize: function() {
	    	this.model.on('change', this.destroye, this);
	    	$('body').append($(this.render().el));
		},
	    render: function() {
			this.$el.html(this.template(this.model.toJSON()));
			this.$el.find('.radioinput').buttonset();
			this.$el.find('input[name="duedate"]').datepicker({ dateFormat: "mm-dd-yy" });
			return this;
	    }
	});
	    

	//LOGIN screen item
	var LoginView = Backbone.View.extend({
		tagName: "div",
	    template: _.template($("#template-login-screen").html()),
	    events: {
	      'click button.register': 'register',
	      'click button.login': 'login'
	    },
	    initialize: function() {
	    	$('body').append($(this.render().el));
		},
	    render: function() {
			this.$el.html(this.template());
			return this;
	    },
	    register:function(event){
	    	var $el = $(this.el);
			$("#login-form > .error").hide();
			$("#login-form > .success").hide();
	    	userRegister($el.find('[name$="username"]').val(),$el.find('[name$="password"]').val(),this);
	    },
	    login:function(event){
	    	var $el = $(this.el);
			$("#login-form > .error").hide();
			$("#login-form > .success").hide();
	    	userLogin($el.find('[name$="username"]').val(),$el.find('[name$="password"]').val(),this);
	    },
	    loginSuccess:function( data,textStatus,jqXHR ){
	    	$("#login-form > .success").html("Login succeeded");
	    	$('#login-form > [name$="username"]').val('');
	    	$('#login-form > [name$="password"]').val('');
		  	token = data.token;
		  	$.cookie('_token',token);
		  	document.location.href = "#";

		  	this.remove();
		},
	    RegisterSuccess:function( data,textStatus,jqXHR ){
	    	$('#login-form input[name$="username"]').val('');
	    	$('#login-form input[name$="password"]').val('');
	    	$("#login-form > .success").html("Registration succeeded");
			$("#login-form > .success").show();
		},
		error:function(jqXHR,textStatus,errorThrown){
			if(jqXHR.responseJSON.code){
				$("#login-form > .error").html(jqXHR.responseJSON.message);
				$("#login-form > .error").show();
			}
		},
	});

	function userLogin(username,password,view){
		var self = view;
		Backbone.ajax({
		  url: all_url+"user/login",
		  contentType: "application/json",
		  type: "POST",
		  data: '{"username":"'+username+'","password":"'+password+'"}',
		  success:function(data,textStatus,jqXHR){
		  	view.loginSuccess(data,textStatus,jqXHR);
		  },
		  error:function( data,textStatus,jqXHR ){
		  	view.error( data,textStatus,jqXHR );
		  }
		});
	}

	function userRegister(username,password,view){
		$.ajax({
		  url: all_url+"user/register",
		  contentType: "application/json",
		  type: "POST",
		  data: '{"username":"'+username+'","password":"'+password+'"}',
		  success:function(data,textStatus,jqXHR){
		  	view.RegisterSuccess(data,textStatus,jqXHR);
		  },
		  error:function( data,textStatus,jqXHR ){
		  	view.error( data,textStatus,jqXHR );
		  }
		});
	}

    new AppRouter();

})(jQuery);