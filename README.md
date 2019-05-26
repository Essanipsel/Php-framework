# Php-framework

## Architecture

middleware , your working on a new blog. In your blog you have a administration interface to manage your website. But you want to add a security rule on this website part. You want to be sure that only connected user with enought rights can edit, delete or create contents. In this case you can

## How to use

### Router

To configure the routes inside your project you just have to write them inside /public/router.json. This file allow you to create every route you need and it accepts several parameters.
#### Structure
router.json contain an only Json object, routes. Routes is just an object array where each object correspond to a potential route. When the program is running, it gets the url and compare it to every iterration. Inside each object you have parameters. Be careful, some of them can accept an array while others can only read a string.
#### Parameters
**route:** The routes pattern. You can insert variable parameters in the url like this .../{param}/... By doing this, the programme is going to store the value inside a global variable. You can access to this variable by this way: $GLOBALS['url']['param']['<your_param>']

**title:** this title will be available in the variable $GLOBALS['title']

**controller:** The class object which is going to be executed. Must be in controller's folder

**method:** The method which is going to be called after the object instanciation

**middleware:** As explained is the Architecture part, middlewares are useful to add rules into your programme execution. To add a new rules you have to create a new array inside your middleware object. This array's name has to be the class name of your middle ware. For example, if I want to add a rule about user parameters I will call my array UserManager. In this array you'll add everyrule you want to test in the same class. To create a rule, add an object inside the array with 4 parameters:
  - attr: The class attributes you want to compare
  - operator: The compare operator (==, >=, <=, !=, <, >)
  - value: The value to compare with
  - error: the relative url to use if the test doesn't work. (ex: /error/403)
For a better understanding here's an example of middleware:
"middleware": {
  "UserManager": [
    {
      "attr": "levelUser",
      "operator": ">=",
      "value": 5,
      "error": "/error/403"
    },
    {
      "attr": "nameUser",
      "operator": "==",
      "value": "Alain",
      "error": "/error/500
    }
  ]
}

### Model
The model is not really an ORM but more something like a MySQL request builder. By two way you can interact with the database without writing any SQL code. The model currently manage 3 basics requests: 
  - SELECT,
  - INSERT,
  - UPDATE

In those requests you can use few paramters (depends on the request): 
  WHERE, 
  VALUES (for INSERT request), 
  SET (UPDATE request), 
  LIMIT. 

To complete a request you have several parameters to give:
  - The table (mandatory)
  - The scope (default: *)
  - the paramters you want to add
#### First method - w/ request builder methods
The easiest way to build a request is to use the methods from RequestBuilder. they allow you to add every element of your request just by calling methods.
Let's start with the attributes. First of all you can access to the scope and the table just by calling them through the object (ex: $builder->scope = "idUser"). Then you can use the parameters methods:
  - **addWhere:** With add where you can add a new parameter WHERE into you request and it takes 3 parameters + 1 optional: 
    - column: where you wanna search the value
    - operator
    - value
    - linkOperator (optional): If you have to test more than one condition, you have to add an operator (AND / OR) after the first one.
  - **addValue:** In INSERT and UPDATE requests you have to give one or many values. For both request you have to use the method addValue. For each value you have indicate the column in the database (1st param) and its value (2nd).
  - **addValues:** Same than addValue but you can send an array composed by the two needed values.
  - **addLimit:** The first paramters is the start point and the second is the length of the return array.
Now you have precised every parameters you need, you can call any execusion method:
  - **find:** execute a SELECT request. Require a table,
  - **findOne**: Same than find but return just one array with all values inside. (no index)
  - **create** execute an INSERT request. Require a table and at least one value
  - **update** execute an UPDATE request. Require at least a table, one value and on where condition.
  - **delete** Work in progress
#### Second method - w/o builder
Each execution method (find, findOne, create, update, delete) in the RequestBuilder can be called with a paramters. If you pass a value to those methods they will ignore their attribuits and use the array. Here's an architecture example for the array
$builder->update(array(
    "table" => "Articles",
    "values" => array(
        "titleArticle" => "Hello world",
        "autorArticle" => "Nicolas"
    ),
    "where" => array(
        "titleArticle" => array(
            "operator" => "=",
            "value" => "Lorem Ipsum"
        )
    )
)));
This method is working in exactly the same way than the first one. It's just easier to use methods if you have to build complex requests.
