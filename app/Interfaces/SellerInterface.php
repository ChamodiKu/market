<?php

/**
 * Page Title : SellerService .
 *
 * Filename : SellerInterface.php
 *
 * Description: Seller details
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
use App\Http\Requests\SellerRequest;

interface SellerInterface
{

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * create new Seller
     * @category
     *
     * @param SellerRequest $request Input - post data of Seller data
     *
     * @var
     *
     * @return array    |        | Seller data
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
    public function create(SellerRequest $request);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * Update exisiting Seller
     * @category
     *
     * @param SellerRequest $request Input - post data of Seller data
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
    public function update(SellerRequest $request, $id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * Delete exisiting Seller
     * @category
     *
     * @param SellerRequest $request Seller data delete
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
     * View Seller by given id
     * @category
     *
     * @param SellerRequest $request Seller data view
     *
     * @var
     *
     * @return array    |        | Seller data according to given id
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
//     public function viewSellerById($id);

    /**
     * @author Chamodi Kulathunga.
     * @author Function Creation Date: 04/02/2023.
     *
     * View Sellers data
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
    public function viewSellers($id=null);
}
