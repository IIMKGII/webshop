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
        if ((count($this->messages) === 0)) {
            $this->business();
        } else {
            $this->twigParams['product_name']= $_POST['product_name'];
            $this->twigParams['price']= $_POST['price'];
            $this->twigParams['short_description']= $_POST['short_description'];
            $this->twigParams['long_description']= $_POST['long_description'];
            $this->twigParams['selected']= Utilities::sanitizeFilter($_POST['product_category_name']);
            $this->twigParams['active']= $_POST['active'];
            $this->twigParams['messages']= $this->messages;
            $this->twigParams['productCategory']= $this->fillProductCategory();;
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
            return true;
    }

    /**
     * product_name is checked for uniqueness against the table onlineshop.product.
     *
     * @return bool false, if product_name in table onlineshop.product already exist, else true.
     */
    private function isUniqueProductName(): bool
    {
            return true;
    }

    /**
     * Stores the product data in the table onlineshop.product.
     *
     * @return void Returns nothing
     */
    private function addProduct(): void
    {
    }
}
