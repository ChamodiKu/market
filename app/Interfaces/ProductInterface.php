<?php

/**
 * Page Title : ProductService .
 *
 * Filename : ProductInterface.php
 *
 * Description: Product details
 *
 * @package
 * @category
 * @version Release: 1.0.0
 * @since File Creation Date: 02/04/2023.
 *
 * @author
 * - Development Group :
 * - Company :
 *
 * @copyright  Copyright © 2023
 *
 *
 */

namespace App\Interfaces;

use Illuminate\Http\Request;
use Exception;
use App\Http\Requests\ProductRequest;

interface ProductInterface
{

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * create new Product
     * @category
     *
     * @param ProductRequest $request Input - post data of Product data
     *
     * @var
     *
     * @return array    |        | Product data
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function create(ProductRequest $request);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * Update exisiting Product
     * @category
     *
     * @param ProductRequest $request Input - post data of Product data
     *
     * @var
     *
     * @return array    |        |
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function update(ProductRequest $request, $id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * Update exisiting Product
     * @category
     *
     * @param ProductRequest $request Product data delete
     *
     * @var
     *
     * @return array    |        |
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function delete($id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View Product by given id
     * @category
     *
     * @param ProductRequest $request Product data view
     *
     * @var
     *
     * @return array    |        | Product data according to given id
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
//     public function viewProductById($id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View Products data
     * @category
     *
     * @var
     *
     * @return
     *
     * @throws Exception
     *
     * @uses
     *
     * @version 1.0.0
     *
     * @since .
     *
     */
    public function viewProducts($id=null);
}
