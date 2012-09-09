namespace Zym\Search;

class Query implements QueryInterface
{
    private $query;
    private $limit;
    private $offset;

    public function getQuery()
    {
        return $this->query;;
    }

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    public function getOffset()
    {
        return $this->offset;
    }

    public function setOffset($offset)
    {
        $this->offset = $offset;
    }
}