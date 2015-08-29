
//TODO MODEL
var TodoModel = Backbone.Model.extend({
	defaults:{
		title:  "",
		duedate:  "",
		priority:  ""
	},
	urlRoot: function(){
		return 'todo/?token='+token;
	}
});

var TodoCollection = Backbone.Collection.extend({
	model: TodoModel,
		url: 'todo/',
	//This is our Friends collection and holds our Friend models
	initialize: function (models, options) {
		this.bind("add", options.view.addTodo,options.view);
		//Listen for new additions to the collection and call a view function if so
	}
});