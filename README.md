# mscode
A lightweight yet advanced PHP web framework by Samim Shekh. Designed for speed, clean architecture, and modern routing. Perfect for building scalable and structured web applications.
Perfect! Aapka **mscode framework setup process** kaafi simple aur clean hai. Neeche usi ko main `README.md` ke **Setup** section ke roop me likh raha hoon — Roman Hindi me, user-friendly tarike se:

---

## ⚙️ mscode Setup Guide

Aap mscode ko niche diye gaye simple steps ke zariye easily setup kar sakte ho:

---

### 🧬 1. Project Clone karo

```bash
git clone https://github.com/samimshekh/mscode.git
cd mscode
composer dump-autoload
````

---

### 🛢️ 2. Database Configuration (Agar project me table/migration use ho)

1. `logic\Settings\Repository.php` file open karo
2. Yahan apna database name set karo:

```php
public static $dbname = "ci4tutorial";
```

3. Ab apne MySQL me ek database banao isi naam se:

```sql
CREATE DATABASE ci4tutorial;
```

---

### 🛠️ 3. Schema se sabhi tables banane ke liye CLI command run karo:

```bash
php mscode make:all
```

Yeh command `logic/schema/` ke sabhi class files read karke database me matching tables create kar dega.

---

✅ Ab aapka mscode project ready hai development ke liye.

```

---

## Routing System

## 📡 Route::get() ka Upyog (GET Routing)

Framework ke andar `Route::get()` ek static method hai jiska use HTTP GET request ke liye route define karne ke liye hota hai.

Iska syntax kuch is tarah hota hai:

```php
final public static function get(string $url, string $Processors, ?string $Guard = null);
````

### 🔍 Parameters ka Arth:

* `string $url`
  Ye us URL path ko represent karta hai jise match kiya jayega, jaise `/`, `/mscode`, `/about`, etc.

* `string $Processors`
  Ye string format me diya jata hai jaise `"Home::index"`, jisme:

  * `"Home"` = class ka naam (jo `logic\Processors\Home.php` me hona chahiye)
  * `"index"` = us class ka method jo request ko handle karega
    Iska kaam CodeIgniter ke controller jaisa hota hai.

* `?string $Guard` (optional)
  Ye bhi string format me hota hai jaise `"Auth::check"` ya `"Home::index"`, jisme:

  * `"Auth"` ya `"Home"` = guard class ka naam (jo `logic\Guards\` folder me hona chahiye)
  * `"check"` ya `"index"` = us guard class ka method
    Iska kaam Laravel ke middleware jaisa hota hai — request aage badhne se pehle kuch validate ya authorize karta hai.

---

### 🧠 Example:

```php
Route::get("/", "Home::index", "Home::index");
```

Iska arth hai:

* Jab koi user root URL `/` par aata hai,
* To `logic\Processors\Home.php` file ke `index()` method ko run kiya jayega.
* Lekin usse pehle, `logic\Guards\Home.php` file ke `index()` method ko execute kiya jayega as a middleware.

Agar Guard method kuch validate nahi karta (jaise login nahi hua ho), aur `return false;` hota hai to Processor run nahi hoga — seedha response send kar diya jayega. Agar `return true;` hua to hi Processor run hoga.

---

### 📁 Namespace Mapping (Pehle se Set):

| Type      | Namespace Path      | Folder Location     |
| --------- | ------------------- | ------------------- |
| Processor | `Logic\Processors\` | `logic/Processors/` |
| Guard     | `Logic\Guards\`     | `logic/Guards/`     |
| Hook     | `Logic\Hooks\`       | `logic\Hooks`       |

---

### 🔒 Use-case Guard ke Sath:

```php
Route::get("/dashboard", "Dashboard::show", "Auth::check");
```

Iska matlab:

* `/dashboard` visit karne par `Dashboard::show` execute hoga,
* Lekin sirf tab jab `Auth::check` guard ne allow kiya ho, yani `return true;` kiya ho.

Agar `Auth::check()` ne user ko unauthorized mana ya `return false;` diya, to processor execute nahi hoga.

---

## 🧩 URL Variables aur Hook System

Routing system ka ek aur important feature hai: **URL variables through hooks**
Yani aap `$url` parameter me hi variable define kar sakte ho is syntax ke through:

```
(type name=namespace::method) 
```

### 📌 Example:

```php
Route::get("/userid(int id=home::index)", "Home::profile");
```

Yahan:

* `home::index` ek **hook** hai
* Jab `int id` set hoga, `home::index` call hoga
* Hook ka namespace hota hai: `Logic\Hooks\` (file path: `logic/Hooks/Home.php`)
* URL me multiple variables ho sakte hain, aur ye variables URL ke kisi bhi jagah par ho sakte hain — chahe starting me, middle me, ya end me.

Variable ka type inme se koi bhi ho sakta hai: **int**, **str**, **var**, ya **class**.
🔁 `var` aur `class` ka behavior same hai. aur `str` jaisa hi hai
**Note:** `var` aur `class`  type me sirf ek condition hai ki uska pehla letter **number nahi ho sakta**

Hook ke andar aapko ye variables automatically milenge:

* `$this->id` — URL se jo value aayi
* `$this->type` — variable ka type (jaise `int`, `str`, etc.)
* `$this->App` — framework ka shared context object

### ✅ Hook ka return value:

* Agar `home::index` hook `return true;` karega, tabhi **Guard** aur **Processor** run honge.
* Agar `return false;` hua to request wahi par terminate ho jayegi.

---

## 🔁 Route::group() ka Upyog

`Route::group()` ka use multiple routes ko group karne ke liye hota hai. Isme ek shared **Guard** lagaya ja sakta hai jo sabhi inner routes pe apply hota hai.

```php
final public static function group($function, ?string $Guards = NULL)
```

### ✅ Example:

```php
Route::group(function () {
    Route::get("/", "Home::index");
    Route::get("/dashboard", "Dashboard::index");
}, "Auth::check");
```

Yahan `Auth::check` sabhi routes pe apply hoga.

---

## 🧠 Execution Flow (Hook → Group → Guard → Processor)

Request ka execution flow kuch is tarah hota hai:

```
Hook → Group Guard → Route Guard → Processor
```

* Agar **Hook** `return false` karta hai, to baaki kuch run nahi hota.
* Agar **Group Guard** `return false` karta hai, to route guard aur processor skip ho jate hain.
* Agar **Route Guard** `return false` karta hai, to processor run nahi hota.
* **Processor** ka return value matter nahi karta — woh hamesha last me execute hota hai agar sab pass ho gaya ho.

---

## 📦 Predefined Variables (Auto-Available Context)

Sabhi layers me kuch predefined variables milte hain:

| Layer     | Predefined Vars                                  |
| --------- | ------------------------------------------------ |
| Hook      | `$this->id`, `$this->type`, `$this->App`         |
| Group     | `$this->hooks->hookName->id`, etc.               |
| Guard     | `$this->group->hooks->hookName->id`, etc.        |
| Processor | `$this->Guard->group->hooks->hookName->id`, etc. |

Yani jo public properties kisi bhi previous layer me defined hoti hain, wo next layers me automatically accessible hoti hain.

🔒 **Note:**
Sirf `public` properties hi aage pass hoti hain. Agar aap kisi class me `protected` ya `private` variable likhoge to wo next layer me access nahi hoga.

---

Aapka `Route` system kaafi advanced aur flexible hai, jisme **HTTP method-specific routing** implement ki gayi hai — Laravel aur Symfony jaise frameworks ki tarah.

Neeche main is poore `Route` method system ka **professional Roman Hindi** me explanation de raha hoon — `README.md` ke format me:

---

## 🌐 Route Method System (HTTP Method-wise Routing)

mscode framework me har HTTP method ke liye dedicated static function diya gaya hai, jiska kaam sirf tabhi execute hota hai jab request ka HTTP method usi se match kare.

Sabhi method ka common signature hai:

```php
final public static function method(string $url, string $Processors, ?string $Guard = null)
````

* `$url` — route path jaise `/user`, `/api/data`
* `$Processors` — `"ClassName::method"` format me processor class
* `$Guard` — (optional) `"ClassName::method"` format me guard class

---

### ✅ Individual HTTP Methods:

| Method    | Function Name      | Description                        |
| --------- | ------------------ | ---------------------------------- |
| `GET`     | `Route::get()`     | Sirf GET request handle karta hai  |
| `POST`    | `Route::post()`    | Sirf POST request handle karta hai |
| `PUT`     | `Route::put()`     | Sirf PUT request ke liye           |
| `DELETE`  | `Route::delete()`  | Sirf DELETE request ke liye        |
| `PATCH`   | `Route::patch()`   | Sirf PATCH ke liye                 |
| `OPTIONS` | `Route::options()` | OPTIONS request handle karta hai   |
| `HEAD`    | `Route::head()`    | HEAD request ke liye               |
| `CONNECT` | `Route::connect()` | CONNECT method ke liye             |
| `TRACE`   | `Route::trace()`   | TRACE method ke liye               |

---

### 🧠 Internal Working:

* Agar match karta hai to Hook → Group → Guard → Processor execution flow start karta hai

---

### 🔁 Route::match()

```php
Route::match(['GET', 'POST'], "/submit", "Form::handle");
```

* Isme aap multiple methods pass kar sakte ho
* Agar current request ka method inme se kisi se match kare to route activate hota hai

---

### ♾️ Route::all()

```php
Route::all("/webhook", "Webhook::handler");
```

* Isme method check nahi hota
* Har request method (`GET`, `POST`, etc.) par ye route kaam karta hai

---

### 🧪 Real-World Example:

```php
Route::get("/user", "User::index");
Route::post("/user", "User::store");
Route::put("/user/(int id=User::find)", "User::update");
Route::delete("/user/(int id=User::find)", "User::delete");
```

* GET => `/user` → `User::index()`
* POST => `/user` → `User::store()`
* PUT => `/user/1` → `User::update()`
* DELETE => `/user/1` → `User::delete()`

---

### 🛡️ Guard Support:

Har method me optional third parameter `$Guard` hota hai:

```php
Route::post("/admin", "Admin::panel", "Auth::check");
```

* Guard check hoga pehle
* Agar `return true` karta hai to hi processor chalega

---

### 📌 Summary:

| Method           | Trigger Conditions                         |
| ---------------- | ------------------------------------------ |
| `Route::get()`   | Jab GET ho                                 |
| `Route::match()` | Jab method allowed list me ho              |
| `Route::all()`   | Hamesha chalega                            |

---

## 🗃️ Repository System (Model Layer)

mscode framework me `logic\Repository\` folder ek Repository system ke roop me kaam karta hai — jo CodeIgniter ke Model ki tarah hota hai.

Yeh sabhi database-related kaam ke liye ek **clean aur reusable structure** provide karta hai.

---

### 📁 Folder: `logic\Repository\`  
Namespace: `Rep`

Is folder me aap apne database model classes bana sakte ho. Jaise:

```php
namespace Rep;

use Logic\Repository\DB;

class News 
{
    public static function getdata(string $name)
    {
        $sql = "SELECT * FROM `$name`";
        $result = DB::query($sql);

        if (!$result) {
            throw new \Exception("Query failed: " . DB::get_error());
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
````

---

### 🧠 Configuration

Database ka connection detail define hota hai `logic\Settings\Repository.php` me:

```php
<?php
namespace Settings;

class Repository 
{
    public static $use = true;
    public static $host = "localhost";
    public static $username = "root";
    public static $password = "";
    public static $dbname = "ci4tutorial";
}
```

> 🔧 Aap yahan apne database credentials set karte ho.
> `public static $use = true;` hone par database auto-connect hoga.

---

### 🧰 DB Class Features

`DB` class (`Logic\Repository\DB`) aapko PHP ke **mysqli** ke sabhi functions static form me provide karti hai, jaise:

* `DB::query($sql)`
* `DB::fetch_assoc($result)`
* `DB::get_error()`
* `DB::insert_id()`
* `DB::escape_string($str)`

Iska fayda ye hai ki aap kisi bhi repository ya processor me **direct database query** likh sakte ho bina connection handle kiye.

---

### 💡 Usage Anywhere

Aap `Rep\YourRepositoryClass` ko kisi bhi Processor, Hook, ya Guard me use kar sakte ho:

```php
use Rep\News;

$data = News::getdata("news");
```

---

### 🔐 Secure aur Simple

* All queries can be manually written, giving full SQL control.
* `DB` class mysqli ke errors ko handle karta hai.
* `Repository` pattern se logic ko separate aur clean banaya gaya hai.

---

## ⚙️ mscode CLI System

mscode framework ke sath ek built-in CLI tool aata hai (`php mscode`), jiska use aap schemas, tables, processors, guards, hooks, aur repositories ko manage karne ke liye kar sakte ho.

---

### 📁 Schema System (logic/schema)

Schema system database ka pura structure define karta hai.

Har schema file ek class hoti hai jiska  file naam aur clase naam sem honi chaihe:

- **file Naam hi table ka naam hota hai**
- Class ke andar ek `public array $columns` hota hai jisme table ke sabhi columns define hote hain
- Aap engine aur charset bhi specify kar sakte ho

#### 🔸 Example:

```php
<?php
namespace Logic\Schema;

class news {
    public array $columns = [
        'id' => 'INT AUTO_INCREMENT PRIMARY KEY',
        'title' => 'VARCHAR(255) NOT NULL',
        'body' => 'TEXT',
        'created_at' => 'DATETIME DEFAULT CURRENT_TIMESTAMP'
    ];

    public string $engine  = 'InnoDB';
    public string $charset = 'utf8mb4';
}
````

---

## 🖥 CLI Commands

CLI commands ko run karne ka format:

```bash
php mscode command argument
```

Agar aap help dekhna chahein:

```bash
php mscode --help
```

---

### 📜 Available Commands:

| Command        | Description                                           |
| -------------- | ----------------------------------------------------- |
| `--help`       | Sabhi available commands print karta hai              |
| `print:table`  | Table structure print karta hai (Arg: `<tableName>`)  |
| `make:table`   | Table create karta hai schema ke base pe              |
| `delete:table` | Table drop karta hai database se                      |
| `make:all`     | Sabhi schema files se table create karta hai          |
| `delete:all`   | Sabhi tables drop karta hai                           |
| `make:schema`  | New schema file create karta hai (Arg: `<ClassName>`) |
| `make:Process` | New Processor class banata hai (`logic/Processors/`)  |
| `make:rep`     | New Repository class banata hai (`logic/Repository/`) |
| `make:hook`    | New Hook class banata hai (`logic/Hooks/`)            |
| `make:guard`   | New Guard class banata hai (`logic/Guards/`)          |

---

### ⚡ Example CLI Usage:

```bash
php mscode make:schema User
php mscode make:table User
php mscode print:table User
php mscode delete:table User
php mscode make:all
php mscode delete:all
php mscode make:Process Dashboard
php mscode make:rep News
php mscode make:hook Userid
php mscode make:guard Auth
```

---

### ⚠️ Important Notes:

* **logic/schema** me har file ek class hoti hai, aur uska naam hi table ka naam hota hai.
* Har command internally `logic\Settings\Repository` me defined DB settings ka use karta hai.
* CLI ka command execution `ReflectionFunction` ka use karke validate hota hai — agar argument galat hai to proper error milta hai.

---

## ❌ 404 Error Handling System

mscode framework me jab koi URL kisi bhi `Route::get()` ya `Route::group()` se match nahi karta, tab framework automatically 404 check karta hai.
 

### 📍 Control Flag: `$error404Processors`
### file logic\Settings\Route.php
```php
namespace Settings;

use Logic\Router\BeseRouteing;

class Route extends BeseRouteing 
{
    public static bool|string $error404Processors = false;
}
````

---

### ⚙️ Behavior:

| Condition                                       | Result                                        |
| ----------------------------------------------- | --------------------------------------------- |
| `Route::$error404Processors = false`            | Default framework ka 404 page render hota hai |
| `Route::$error404Processors = "Home::notfound"` | Custom Processor execute hota hai             |

> 🔁 Default value `false` hoti hai.

---

### ✅ Custom 404 Processor Example:

```php
Route::$error404Processors = "Error::page404";
```

Iska matlab hai agar koi route match nahi karta to:

* `logic\Processors\Error.php` file ke
* `page404()` method ko call kiya jayega

```php
namespace Logic\Processors;

class Error 
{
    public function page404()
    {
        $this->App->Response->setresponseCode(404);
        $this->App->Response->setHtml("Custom 404 Page Not Found.");
        return false;
    }
}
```

---

### 📄 Notes:

* Yeh processor framework ke normal route execution ke pattern ko follow karta hai.
* Aap is method me custom HTML, JSON response, ya logging bhi likh sakte ho.
* Agar `Route::$error404Processors` set nahi kiya gaya (ya `false` hai), to internal default 404 page render hota hai — jo production me customize karne layak hai.

---

Aapke framework `mscode` me `Hook → Group → Guard → Processor` chain ke har level par ek special object available hota hai:

```php
$this->App
```

Is `App` object me do powerful tools milte hain jo HTTP request aur response ko handle karte hain:

* `$this->App->Request` → Client se aayi request ko manage karta hai
* `$this->App->Response` → Server se bheje jaane wale response ko manage karta hai

Yeh dono classes framework ke namespace `Logic\Http` ke andar defined hain.

---

## ✅ `App->Request` Class: Client Request ko Read Karne ke Liye

```php
public Request $Request;
```

Namespace: `Logic\Http\Request`

### 🔍 Public Methods:

| Method                | Description                                                                                          |
| --------------------- | ---------------------------------------------------------------------------------------------------- |
| `getUrl(): string`    | Current URL path return karta hai, jaise `/`, `/blog/1`                                              |
| `getMethod(): string` | HTTP method return karta hai, jaise `GET`, `POST`, etc.                                              |
| `decode(): array`     | API request ke body ko read karke usse `JSON`, `XML`, ya `form-urlencoded` format me parse karta hai |

#### 📦 Example Usage:

```php
$url = $this->App->Request->getUrl(); // "/dashboard"
$method = $this->App->Request->getMethod(); // "POST"
$data = $this->App->Request->decode(); // ['title' => 'Hello', 'body' => '...']
```

#### 💡 decode() kya karta hai?

* Agar `Content-Type: application/json` ho → JSON decode karta hai
* Agar `Content-Type: application/xml` ho → XML decode karta hai
* Agar `application/x-www-form-urlencoded` ho → `parse_str` use karta hai

---

## ✅ `App->Response` Class: Server Response Send Karne ke Liye

```php
public Response $Response;
```

Namespace: `Logic\Http\Response`

### 🔍 Public Methods:

| Method                                                                    | Description                                                        |
| ------------------------------------------------------------------------- | -------------------------------------------------------------------|
| `setresponseCode(int $code)`                                              | HTTP response code set karta hai, jaise `404`, `200`               |
| `header(string $name, string $value)`                                     | Custom header add karta hai                                        |
| `setHtml(string $html, bool $flag = false)`                               | HTML response set karta hai                                        |
| `setJson(array $data, bool $flag = false)`                                | JSON response set karta hai                                        |
| `setXml(array $data, bool $flag = false, string $rootNode = 'response')`  | XML response set karta hai $rootNode Custom node defaint karta hai |
| `setXhtmlxml(array $data, bool $flag = false, string $rootNode = 'html')` | XHTML+XML content bhejne ke liye                                   |
| `setXhxml(array $data, bool $flag = false, string $rootNode = 'hxml')`    | Custom x-hxml format bhejne ke liye                                |
| `cleneBody()`                                                             | Body reset karta hai (clear karta hai)                             |
| `cleneHeader()`                                                           | Headers reset karta hai except `Content-Type` (agar set hai)       |
| `getBody()`                                                               | Current response body return karta hai (array format)              |

---

### 🛠 Example Usage (JSON Response):

```php
$this->App->Response
    ->setresponseCode(200)
    ->setJson(["success" => true])
```

### 🛠 Example Usage (HTML Response):

```php
$this->App->Response
    ->setHtml("<h1>Page Not Found</h1>")
    ->setresponseCode(404)
```

---

## 📌 Important Behavior:

* `send()` method internally `http_response_code()`, `header()` aur body output handle karta hai. is liye `send()` kabhi bhi `Hook → Group → Guard → Processor` is te mal nahi kar ni chai he internally call hota hai
* Response type (`HTML`, `JSON`, `XML`) set hone ke baad wahi format lock ho jata hai (jab tak `flag = true` na ho).
* `setJson`, `setHtml`, `setXml` me `flag = true` dena purane content type ko overwrite karta hai.   

---

## ✅ Conclusion (User's Point of View):

* `$this->App->Request` = Read incoming data
* `$this->App->Response` = Prepare and send response

### ⚙ Best Practice:

1. **Request se data lo**:

   ```php
   $data = $this->App->Request->decode();
   ```

2. **Logic chalao (DB, validation, etc.)**

3. **Response prepare karo**:

   ```php
   $this->App->Response->setJson(["ok" => true])->send();
   ```

---

## 📤 Response Handling Guidelines (User ke Point of View se)

mscode framework me response system `App->Response` ke through control kiya jata hai. Iska use mostly **API development** me hota hai (HTML, JSON, XML, etc. bhejne ke liye).

---

### ❌ ager ek bar $this->App->Response->set* kar liya hai to echo, print ka use nahi karna

Framework ke andar `Hook → Group → Guard → Processor` ke execution ke dauraan agar aap `echo`, `print`, ya `var_dump` ka use karoge to **koi output nahi dikhega**. ager $this->App->Response->set* use nahi kiya to dikhega yai sa sliye hai api me json/xml text me kharabi naho

```php
$this->App->Response->set*
echo "test"; // ❌ Ye kabhi print nahi hoga
````

Kyunki mscode framework internal output buffer aur response handler ka use karta hai.

---

### ✅ Hamesha `$this->App->Response->set*()` ka use karo

```php
$this->App->Response->setJson([...]);
$this->App->Response->setHtml("<h1>Hello</h1>");
```

Iska fayda:

* Format controlled hota hai (HTML, JSON, XML, etc.)
* Response auto-merge ya replace hota hai
* End me `send()` call karke data client ko bheja jata hai

---

### 🔄 Ek format set hone ke baad wahi format continue hota hai

Agar aapne ek baar JSON set kar liya:

```php
$this->App->Response->setJson([...]);
```

To aap aage bhi `setJson()` hi use karoge — agar aap `setHtml()` ya `setXml()` use karne ki koshish karoge to **kuch bhi set nahi hoga**.

---

### 🔃 Format change karna ho to `flag = true` use karo

Agar aap format change karna chahte ho, to manually `flag = true` pass karo:

```php
$this->App->Response->setHtml("<p>Forcefully changed</p>", true);
```

Isse pehle ka content hata diya jayega, aur response format reset ho jayega.

---

### 🧠 Multiple set\*() calls allowed hain

Aap multiple bar response add kar sakte ho — pehle set karoge format, fir usme naye content add karte jaoge:

```php
$this->App->Response->setJson(['step1' => true]);
$this->App->Response->setJson(['step2' => true]);
```

Output hoga:

```json
{
  "step1": true,
  "step2": true
}
```

---

### 🚫 User ko `send()` function manually call nahi karna chahiye

Framework end me internally `send()` call karta hai, isliye aapko manually `send()` nahi chalana chahiye unless aap custom response stream create kar rahe ho.

Aap sirf `set*()` methods se response prepare karo — framework khud hi client ko bhej dega.

---

### 🧹 Clear/Reset Karna ho to:

```php
$this->App->Response->cleneBody();   // Body reset
$this->App->Response->cleneHeader(); // Headers reset 
```

---

### ✅ Summary (Best Practice):

| Kaam                  | Kya karein                              | Kya na karein                 |
| --------------------- | --------------------------------------- | ----------------------------- |
| Output dikhana        | `$this->App->Response->setJson()`       | `echo`, `print`               |
| Multiple data bhejna  | Ek hi format me multiple `set*()` calls |  kar sakte ho                 |
| Format change karna   | `flag = true` use karein                | Format auto-switch na sochein |
| Final response bhejna | `send()` call na karein (auto hota hai) | `send()` spam na karein       |

---
