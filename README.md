# 介绍

一个基于laravel，方便数据库字段类型说明的拓展



# 用法

使用composer导入包。

```php
composer require syhcode/field-note
```

在 Laravel 5.5 或以上，服务提供商将自动获得注册。 在旧版本的框架中，只需在 `config/app.php` 文件中添加服务提供者即可。

```php
'providers' => [
    // ...
    FileNote\NoteServiceProvider::class,
];
```

发布迁移。

```
php artisan vendor:publish --provider="FileNote\NoteServiceProvider"
```

创建表字段说明表。

```
php artisan migrate
```

在model使用`use`引用`NoteTrait`，可添加 `$note_fields` 为默认预加载的类型说明的字段。

```php
<?php

namespace App\Models;

use App\ModelService\NoteTrait;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use NoteTrait;
  	protected $note_fields = ["type"];
}

```

使用 `note()` ，传参只会预加载参数里面包含的字段说明，不传参自动预加载 `model` 里面 `$note_fields` 的字段说明。

```php
<?php

namespace App\Http\Controllers;

use App\Models\Member;


class HomeController extends Controller
{
    public function index(){

      	//不传参自动预加载 model 里面 $note_fields 的字段说明
        $member = Member::note()->first();
        //单个字段
 				$member = Member::note('type')->first();
      	//多个字段
        $member = Member::note('type','level')->first();
        $member = Member::note(['type','level'])->first();
    }
}

```

可以通过字段名加 `env` 配置项 `FILED_NOTE_SUFFIX` 获取字段说明，`FILED_NOTE_SUFFIX` 默认为 `Note`，`type `字段默认为 `typeNote`。

```php

$member->typeNote->note; 

$member->levelNote->note; 

```

获取 `model `所有的字段说明。

```php
$member->getAllNote();
```

获取字段的所有说明。

```php
$member->getNoteByField('type');
```

获取字段的某个值对应说明。

```php
$member->getNote('type','1');
```

检查字段某个值是否有对应说明，可用在表单验证。

```php
$member->hasNote('type','1');
```

