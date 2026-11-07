<?php

namespace PEAR2\Net\RouterOS\Test;

require_once __DIR__ . '/TestCase.php';

use PEAR2\Net\RouterOS\Query;

class QueryTest extends TestCase
{
    public function testWhereSimple()
    {
        $q = Query::where('name', 'ether1', Query::OP_EQ);
        $this->assertInstanceOf(Query::class, $q);
    }

    public function testLogicalOperations()
    {
        $q = Query::where('disabled', 'false', Query::OP_EQ);
        $q->andWhere('running', 'true', Query::OP_EQ);
        $this->assertInstanceOf(Query::class, $q);

        $qOr = Query::where('type', 'ether', Query::OP_EQ);
        $qOr->orWhere('type', 'vlan', Query::OP_EQ);
        $this->assertInstanceOf(Query::class, $qOr);

        $qNot = Query::where('invalid', 'true', Query::OP_EQ)->not();
        $this->assertInstanceOf(Query::class, $qNot);
    }

    public function testComparisonOperators()
    {
        $qGreater = Query::where('bytes', '1000', Query::OP_GT);
        $this->assertInstanceOf(Query::class, $qGreater);

        $qLess = Query::where('bytes', '500', Query::OP_LT);
        $this->assertInstanceOf(Query::class, $qLess);

        $qExists = Query::where('comment', null, Query::OP_EX);
        $this->assertInstanceOf(Query::class, $qExists);

        $qNotExists = Query::where('comment', null, Query::OP_NEX);
        $this->assertInstanceOf(Query::class, $qNotExists);
    }
}
