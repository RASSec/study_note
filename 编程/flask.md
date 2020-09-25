# flask

## 目录结构

```shell
microblog\
  env\
    <virtual environment files>
  app\
    static\
    templates\
    __init__.py
    views.py
    config.py
  tmp\
  run.py
```

## flask初始化

- 加载配置
  config.py

  ```python
  #跨站请求伪造保护
  CSRF_ENABLED = True
  SECRET_KEY = 'you-will-never-guess'
  
  
  ```

  `__init__.py`加入`app.config.from_object('config')`

-  

## web表单

### 配置跨站请求伪造保护

```python
#跨站请求伪造保护
CSRF_ENABLED = True
SECRET_KEY = 'you-will-never-guess'
```

### 表单创建

#### demo

forms.app

```python
from flask.ext.wtf import Form
from wtforms import StringField, BooleanField
from wtforms.validators import DataRequired

class LoginForm(Form):
    openid = StringField('openid', validators=[DataRequired()])
    remember_me = BooleanField('remember_me', default=False)
```

因为我们并没有在表单中定义提交按钮，我们必须按照普通的字段来定义。提交字段实际并不携带数据因此没有必要在表单类中定义。

views.app

```python
from flask import render_template, flash, redirect
from app import app
from .forms import LoginForm

# index view function suppressed for brevity

@app.route('/login', methods = ['GET', 'POST'])
def login():
    form = LoginForm()
    return render_template('login.html',
        title = 'Sign In',
        form = form)
```



login.html

```html
<!-- extend from base layout -->
{% extends "base.html" %}

{% block content %}
<h1>Sign In</h1>
<form action="" method="post" name="login">
    {{form.hidden_tag()}}
    <p>
        Please enter your OpenID:<br>
        {{form.openid(size=80)}}<br>
    </p>
    <p>{{form.remember_me}} Remember Me</p>
    <p><input type="submit" value="Sign In"></p>
</form>
{% endblock %}
```

#### 接受表单数据

views.py

```python
@app.route('/login', methods = ['GET', 'POST'])
#若methods默认只接受GET数据
def login():
    form = LoginForm()
    if form.validate_on_submit():
        # 检验接受的数据
        flash('Login requested for OpenID="' + form.openid.data + '", remember_me=' + str(form.remember_me.data))
        # 会生成一个messages,可以在模板中用get_flashed_messages()接收
        return redirect('/index')
    return render_template('login.html',
        title = 'Sign In',
        form = form)
```

- 接收flash生成的message

```html
{% with messages = get_flashed_messages() %}
    {% if messages %}
    <ul>
    {% for message in messages %}
        <li>{{ message }} </li>
    {% endfor %}
    </ul>
    {% endif %}
    {% endwith %}
```

- 显示表单验证的错误信息

```html
{% for error in form.openid.errors %}
            <span style="color: red;">[{{ error }}]</span>
          {% endfor %}<br>
```



## 模板

### 模板中控制语句

```html
<html>
  <head>
    {% if title %}
    <title>{{title}} - microblog</title>
    {% else %}
    <title>Welcome to microblog</title>
    {% endif %}
  </head>
  <body>
      <h1>Hello, {{user.nickname}}!</h1>
  </body>
</html>
```



### 模板中的循环语句

```html
<html>
  <head>
    {% if title %}
    <title>{{title}} - microblog</title>
    {% else %}
    <title>microblog</title>
    {% endif %}
  </head>
  <body>
    <h1>Hi, {{user.nickname}}!</h1>
    {% for post in posts %}
    <p>{{post.author.nickname}} says: <b>{{post.body}}</b></p>
    {% endfor %}
  </body>
</html>
```

### 模板继承

将公共部分移动到一个公共模板,并在指定地方插入模板

base.html

```html
<html>
  <head>
    {% if title %}
    <title>{{title}} - microblog</title>
    {% else %}
    <title>microblog</title>
    {% endif %}
  </head>
  <body>
    <div>Microblog: <a href="/index">Home</a></div>
    <hr>
    {% block content %}{% endblock %}
      <!--block标识的模板将会插入在这个地方-->
  </body>
</html>
```

index.html

```html
{% extends "base.html" %}
{% block content %}<!--模板标识-->
<h1>Hi, {{user.nickname}}!</h1>
{% for post in posts %}
<div><p>{{post.author.nickname}} says: <b>{{post.body}}</b></p></div>
{% endfor %}
{% endblock %}
```



## 数据库

我们将使用 [Flask-SQLAlchemy](http://packages.python.org/Flask-SQLAlchemy) 扩展来管理我们应用程序的数据。这个扩展封装了 [SQLAlchemy](http://www.sqlalchemy.org/) 项目，这是一个 [对象关系映射器](http://en.wikipedia.org/wiki/Object-relational_mapping) 或者 ORM。

ORMs 允许数据库应用程序与对象一起工作，而不是表以及 SQL。执行在对象的操作会被 ORM 翻译成数据库命令。这就意味着我们将不需要学习 SQL，我们将让 Flask-SQLAlchemy 代替 SQL。

http://www.pythondoc.com/flask-mega-tutorial/database.html

https://flask-sqlalchemy.palletsprojects.com/en/2.x/quickstart/

### 配置

config.py

```python
import os
basedir = os.path.abspath(os.path.dirname(__file__))

SQLALCHEMY_DATABASE_URI = 'sqlite:///' + os.path.join(basedir, 'app.db')
SQLALCHEMY_MIGRATE_REPO = os.path.join(basedir, 'db_repository')
```

SQLALCHEMY_DATABASE_URI 是 Flask-SQLAlchemy 扩展需要的。这是我们数据库文件的路径。

SQLALCHEMY_MIGRATE_REPO 是文件夹，我们将会把 SQLAlchemy-migrate 数据文件存储在这里。

当我们初始化应用程序的时候，我们也必须初始化数据库。这是我们更新后的初始化文件(文件 *app/__init__.py*):

```python
from flask import Flask
from flask.ext.sqlalchemy import SQLAlchemy

app = Flask(__name__)
app.config.from_object('config')
db = SQLAlchemy(app)

from app import views, models
```

### 建立对象和sql的映射

1. 创建app/models.py

   ```python
   from app import db
   
   class User(db.Model):
       id = db.Column(db.Integer, primary_key = True)
       nickname = db.Column(db.String(64), index = True, unique = True)
       email = db.Column(db.String(120), index = True, unique = True)
       #xx=db.Column(db.type,setting)
   
       def __repr__(self):
           #如何打印对象
           return '<User %r>' % (self.nickname)
   ```



### 创建数据库



```python
#!flask/bin/python
from migrate.versioning import api
from config import SQLALCHEMY_DATABASE_URI
from config import SQLALCHEMY_MIGRATE_REPO
from app import db
import os.path
db.create_all()
if not os.path.exists(SQLALCHEMY_MIGRATE_REPO):
    api.create(SQLALCHEMY_MIGRATE_REPO, 'database repository')
    api.version_control(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
else:
    api.version_control(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO, api.version(SQLALCHEMY_MIGRATE_REPO))
```

### 迁移数据库

创建迁移存储库:`flask db init`

生成迁移脚本:`flask db migrate -m "users table" `

迁移数据库:` flask db upgrade `

```python
#!flask/bin/python
import imp
from migrate.versioning import api
from app import db
from config import SQLALCHEMY_DATABASE_URI
from config import SQLALCHEMY_MIGRATE_REPO
v = api.db_version(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
migration = SQLALCHEMY_MIGRATE_REPO + ('/versions/%03d_migration.py' % (v+1))
tmp_module = imp.new_module('old_model')
old_model = api.create_model(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
exec(old_model, tmp_module.__dict__)
script = api.make_update_script_for_model(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO, tmp_module.meta, db.metadata)
open(migration, "wt").write(script)
api.upgrade(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
v = api.db_version(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
print('New migration saved as ' + migration)
print('Current database version: ' + str(v))
```

### 数据库升级

如果有数据库迁移的支持，当你准备发布新版的时候，你只需要录制一个新的迁移，拷贝迁移脚本到生产服务器上接着运行脚本，所有事情就完成了。数据库升级也只需要一点 Python 脚本(文件 db_upgrade.py):

```python
#!flask/bin/python
from migrate.versioning import api
from config import SQLALCHEMY_DATABASE_URI
from config import SQLALCHEMY_MIGRATE_REPO
api.upgrade(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
v = api.db_version(SQLALCHEMY_DATABASE_URI, SQLALCHEMY_MIGRATE_REPO)
print('Current database version: ' + str(v))
```



### 字段属性

```
db.Integer,db.String(140), db.ForeignKey('user.id'),db.DateTime，primary_key = True，unique，index
```



### 操作

db.session.add(x)

db.session.commit()

models.字段名.query.get(xx)

models.字段名.query.all(xx)

db.session.delete()



## flask插件初始化

```python
class Config(object):
    # ...
    SQLALCHEMY_DATABASE_URI = os.environ.get('DATABASE_URL') or \
        'sqlite:///' + os.path.join(basedir, 'app.db')
    SQLALCHEMY_TRACK_MODIFICATIONS = False
app.config.from_object(Config)
   
```





## 备忘

```shell
#创建虚拟环境
python -m venv flask
# 安装flask的依赖包
# windows环境
flask\Scripts\pip install flask
flask\Scripts\pip install flask-login
flask\Scripts\pip install flask-openid
flask\Scripts\pip install flask-mail
flask\Scripts\pip install flask-sqlalchemy
flask\Scripts\pip install sqlalchemy-migrate
flask\Scripts\pip install flask-whooshalchemy
flask\Scripts\pip install flask-wtf
flask\Scripts\pip install flask-babel
flask\Scripts\pip install guess_language
flask\Scripts\pip install flipflop
flask\Scripts\pip install coverage
# 运行
flask\Scripts\python run.py
```

- 访问post/put数据

`print(request.form)`

- 访问get数据

`value = request.args.get('key', '')`

