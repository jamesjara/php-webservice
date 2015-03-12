# php-webservice-
php x7cloud webservice

REST on top of CRUD

» HTTP Methods
GET	To fetch a resource
POST To create a new resource
PUT	To update existing resource
DELETE To delete a resource

» HTTP Status Code
tells client application what action should be taken with the response.

» URL Structure 
In REST design the URL endpoints should be well formed and should be easily understandable.

» API Versioning
http://stackoverflow.com/questions/389169/best-practices-for-api-versioning


» Content Type
The Content Type in HTTP headers specifies the kind of the data 

» API Key
private API to restrict the access or limit to a private access

» API auto generated documentation


== for debuging use ===
 Chrome Advanced REST client extension for Testing
 https://chrome.google.com/webstore/detail/advanced-rest-client/hgmloofddffdnphfgcellkdfbfbjeloo
 
 
best way to use:
- create core(bussines logic) with active record (CRUD)
- handle comunication with this framework (REST)


eXAMPLE
'''
   <?php
   // Load Core(active record)
   require_once __DIR__ . '/../../../core/load_core.php';
   use Illuminate\Database\Capsule\Manager as Capsule;
   
   // Load Webservice Core
   require_once __DIR__ . '/../../x7cloud/vendor/autoload.php';
   use JamesJara\X7Cloud\X7Cloud;
   use JamesJara\X7Cloud\Response\Models\ResponseExt;
   
   class BuildToolsApi extends X7Cloud
   {
    public function __construct()
    {
        parent::__construct();
        $this->prefix = "bt_";
        $this->debug = true;
    }
    
    protected function bt_users()
    {
        $response = new ResponseExt();
        $response->setMetadata(Users::metadata());
        $sid = $this->args[0];

        if ($this->method == 'GET') {

            $response->setType(0);
            $response->setSuccess(true);
            $response->setData(Users::getAll());
        } elseif ($this->method == 'PUT') {

            $data = json_decode($this->payload, true);
            $affectedRow = Users::change($data);
            $response->setData($affectedRow);
            $response->setSuccess(empty($affectedRows) ? true : false);
        } elseif ($this->method == 'POST') {

            $data = json_decode($this->payload, true);
            $affectedRow = Users::insert($data);
            $response->setData($affectedRow);
            $response->setSuccess(empty($affectedRows) ? true : false);
            $response->showMetadata(false);
        } elseif ($this->method == 'DELETE') {

            $data = $this->args[0];
            $affectedRows = Users::remove($data);
            $response->setSuccess(empty($affectedRows) ? true : false);
            $response->showMetadata(false);
        }

        return $response;
    }
   }
'''
 
