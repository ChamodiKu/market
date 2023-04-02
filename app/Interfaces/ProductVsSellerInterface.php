<?php

/**
 * Page Title : ProductVsSellerService .
 *
 * Filename : ProductVsSellerInterface.php
 *
 * Description: ProductVsSeller details
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
use App\Http\Requests\ProductVsSellerRequest;

interface ProductVsSellerInterface
{

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * create new Product Vs Seller assignment
     * @category
     *
     * @param ProductVsSellerRequest $request Input - post data of Product vs seller data
     *
     * @var
     *
     * @return array    |        | Product vs seller data
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
    public function assign(ProductVsSellerRequest $request);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * update Product Vs Seller assignment
     * @category
     *
     * @param ProductVsSellerRequest $request Input - post data of Product vs seller data
     *
     * @var
     *
     * @return array    |        | Product vs seller data
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
    public function update(ProductVsSellerRequest $request);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * Remove Product Vs Seller assignment
     * @category
     *
     * @param ProductVsSellerRequest $request Input - post data of Product vs seller data
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
    public function unassign(ProductVsSellerRequest $request);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View all Product Vs Seller assignments
     * @category
     *
     * @param
     *
     * @var
     *
     * @return array    |        | All Product Vs Seller assignments
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
    public function viewAllProductVsSellerAssignments();

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View Seller by given product id
     * @category
     *
     * @param
     *
     * @var
     *
     * @return array    |        | All seller data according to given product id
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
     public function viewSellerByProduct($id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View Products by given Seller id
     * @category
     *
     * @var
     *
     * @return array |  | All products by given seller id
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
    public function viewProductBySeller($id);
}
