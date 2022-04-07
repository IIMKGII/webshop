<?php
namespace Exercises;

use PDO;
use Utilities\Utilities;

/*
 * The class Product stores product data in onlineshop.product.
 *
 * @author Martin Harrer <martin.harrer@fh-hagenberg.at>
 */
final class Product
{
    /**
     * @var array messages is used to display error and status messages after a form was sent an validated
     */
    private array $messages = [];

    /**
     * @var object twig provides a Twig object to display hmtl templates
     */
    private object $twig;

    /**
     * @var array twigParams is used to set variables passed to Twig
     */
    private array $twigParams = [];

    /**
     * Product constructor.
     *
     * Initializes Twig
     * Creates a database handler for the database connection.
     */
    public function __construct($twig)
    {
        $this->twig=$twig;
        $this->initDB();
    }

    /*
     * Initialize database connection
     *
     * @return void Returns nothing
     */

    private function initDB(): void
    {
        $charsetAttr="SET NAMES utf8 COLLATE utf8_general_ci";
        $dsn="mysql:host=db;port=3306;dbname=onlineshop";
        $mysqlUser="onlineshop";
        $mysqlPwd="geheim";
        $multi=false;
        $options = array(
            // A warning is given for persistent connections in case of a interrupted database connection.
            // This warning is shown on the web page if error_reporting=E_ALL is set in php.ini
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_NUM,
            // PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
            PDO::MYSQL_ATTR_INIT_COMMAND => $charsetAttr,
            PDO::MYSQL_ATTR_MULTI_STATEMENTS => $multi
        );
        $this->dbh = new PDO($dsn, $mysqlUser, $mysqlPwd, $options);
    }

    /**
     * Validates the user input
     *
     * All fields are required.
     * product_name is checked for uniqueness against onlineshop.product.product_name.
     *
     * @see Product::isUniqueProductName().
     * Price can be validated with Utilities::isPrice().
     * ptype is checked against onlineshop.product_category, if it exists.
     * @see Product::isValidCategory().
     *
     * Error messages are stored in the array $messages[].
     * Calls Login::business() if all input fields are valid.
     *
     * @return void Returns nothing
     */
    public function isValid(): void
    {
        if (Utilities::isEmptyString($_POST['product_name'])){
            $this->messages['product_name'] = "Please enter a product.";
        }
        if (Utilities::isEmptyString($_POST['price'])){
            $this->messages['price'] = "Please enter a price.";
        }
        if (!Utilities::isInt($_POST['price'])){
            $this->messages['price'] = "Please enter a valid price.";
        }
        if (isset($_POST['active'])){
            $this->messages['active'] = "Please select active/inactive";
        }
        if (Utilities::isEmptyString($_POST['short_description'])){
            $this->messages['short_description'] = "Please enter a short description.";
        }
        if (Utilities::isEmptyString($_POST['long_description'])){
            $this->messages['long_description'] = "Please enter a long description.";
        }
        if(!Utilities::isEmptyString($_POST['product_name']) && !$this->isUniqueProductName()) {
            $this->messages['product_name'] = "This product already exists";
        }
        if(!Utilities::isEmptyString($_POST['product_category_name']) && !$this->isValidCategory()) {
            $this->messages['product_category_name'] = "Please select a valid category";
        }
        if ((count($this->messages) === 0)) {
            $this->business();
        } else {
            $this->twigParams['product_name'] = $_POST['product_name'];
            $this->twigParams['price'] = $_POST['price'];
            $this->twigParams['short_description'] = $_POST['short_description'];
            $this->twigParams['long_description'] = $_POST['long_description'];
            $this->twigParams['selected'] = Utilities::sanitizeFilter($_POST['product_category_name']);

            if (isset($_POST['active'])) {
            $this->twigParams['active'] = $_POST['active'];
        }

            $this->twigParams['messages']= $this->messages;
            $this->twigParams['productCategory']= $this->fillProductCategory();
            $this->twig->display("product.html.twig", $this->twigParams);
        }
    }

    /**
     * Process the user input, sent with a POST request
     *
     * Calls Product::addProduct(), to store the validated data in the table onlineshop.product.
     * On success $this->message['status'] is set and sent to the template.
     */
    protected function business(): void
    {
        $this->addProduct();
        $this->messages['status'] = "Your product has been added successfully";
        $this->twigParams['messages']= $this->messages;
        $this->twigParams['productCategory']= $this->fillProductCategory();;
        $this->twig->display("product.html.twig", $this->twigParams);
    }

    /**
     * Returns all entries of the table onlineshop.product_category in an array.
     *
     * @return mixed Array that returns rows of onlineshop.product_category. false in case of error
     */
    public function fillProductCategory(): array
    {
        $result = [];
        $query = <<<SQL
                 SELECT product_category_name 
                 FROM product_category
SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
        }

        return $result;
    }

    /**
     * product_category in $_POST is checked against the table onlineshop.product_category, if it already exists.
     *
     * @return bool true, if product category exists, otherwise false.
     */
    private function isValidCategory(): bool
    {
        $query = /** @lang MySQL */
            <<<SQL
                SELECT product_category_name from product_category
                where product_category_name = :product_category_name;
SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $this->stmt->bindValue(':product_category_name' , $_POST['product_category_name'], PDO::PARAM_STR);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
        }
        return (count($result))===0;
    }

    /**
     * product_name is checked for uniqueness against the table onlineshop.product.
     *
     * @return bool false, if product_name in table onlineshop.product already exist, else true.
     */
    private function isUniqueProductName(): bool
    {
        $query = /** @lang MySQL */
            <<<SQL
                SELECT product_name from product
                where product_name = :product_name;
SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $this->stmt->bindValue(':product_name' , $_POST['product_name'], PDO::PARAM_STR);
            $this->stmt->execute();
            $result = $this->stmt->fetchAll();
        }
        return (count($result))===0;
    }

    /**
     * Stores the product data in the table onlineshop.product.
     *
     * @return void Returns nothing
     */
    private function addProduct(): void
    {
        $query = /** @lang MySQL */
            <<<SQL
                 INSERT INTO  product
                 SET product_name = :product_name,
                     product_category_name = :product_category_name,
                     price = :price,
                     short_description = :short_description,
                     long_description = :long_description,
                     active = :active,
                     date_added = :date_added
        SQL;
        if ($this->dbh) {
            $this->stmt = $this->dbh->prepare($query);
            $params = [":product_name" => $_POST['product_name'],
                ":product_category_name" => $_POST['product_category_name'],
                ":price" => $_POST['price'],
                ":short_description" => $_POST['short_description'],
                ":long_description" => $_POST['long_description'],
                ":active" => $_POST['active'],
                ":date_added" => date('Y-m-d H:i:s')];
            $this->stmt->execute($params);
        }
    }
}
