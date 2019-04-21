<?php

namespace Hea\Controllers;

use Hea\Models\Model;
use Hea\Router\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {

        //display data from database
        $stmt = Model::connect()->prepare("select id from comments");
        $stmt->execute();

        $commentsArr = [];
        $commentsArr["records"] = [];
        $pagesTotal = 0;
        $currentPage = 1;
        $pageItems = 3;
        //number of total rows in database
        $rowsTotal = $stmt->rowCount();
        if ($rowsTotal > 0) {
            //Paging
            //how many page items are displayed
            $pageItems = 3;
            //how many rows per page (minimum will be 2 rows for current layout view)
            $rowsPerPage = 10;

            //number of total pages available
            $pagesTotal = ceil($rowsTotal / $rowsPerPage);

            //current page
            $paramsRequest = $request->getBody();
            $currentPage = isset($paramsRequest['page']) ? is_int_number($paramsRequest['page']) ? $paramsRequest['page'] : 1 : 1;

            //the sql limit starting number of rows on current page
            $firstIndexRowOfCurrentPage = ($currentPage - 1) * $rowsPerPage;

            //retrieve selected rows from database
            $stmt = Model::connect()->prepare("
            select 
            comments.id,
            comments.content,
            comments.updated_at as comment_updated_at,
            u.name,
            u.email
            from comments inner join users u on comments.user_id = u.id
            where comments.deleted_flag!=1 
            order by comments.updated_at DESC
            limit :index,:limit ");

            $stmt->bindValue(':index', (int) trim($firstIndexRowOfCurrentPage), \PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int) trim($rowsPerPage), \PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                // extract row
                // this will make $row['name'] to just $name only
                extract($row);

                $commentsItem = [
                    "id" => $id,
                    "content" => html_entity_decode($content),
                    "comment_updated_at" => $comment_updated_at,
                    "name" => $name,
                    "email" => $email,
                ];

                array_push($commentsArr["records"], $commentsItem);
            }
        }

        self::view('index', [
            'commentsArr' => $commentsArr["records"],
            'pagesTotal' => $pagesTotal,
            'pageItems' => $pageItems,
            'currentPage' => $currentPage,
        ]);
    }
}