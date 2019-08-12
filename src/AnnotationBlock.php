<?php
namespace whitemerry\phpkin;

/**
 * Class AnnotationBlock (Annotations block)
 *
 * @author Piotr Bugaj <whitemerry@outlook.com>
 * @package whitemerry\phpkin
 */
class AnnotationBlock
{
    const SERVER = 'server';
    const CLIENT = 'client';

    /**
     * @var String[]
     */
    protected $values;

    /**
     * @var Endpoint
     */
    protected $endpoint;

    /**
     * @var int
     */
    protected $startTimestamp;

    /**
     * @var int
     */
    protected $endTimestamp;

    /**
     * AnnotationBlock constructor.
     * (Builds 2 annotations)
     *
     * @param $endpoint Endpoint
     * @param $startTimestamp int
     * @param $endTimestamp int Default now
     * @param $type string Default CLIENT
     */
    public function __construct($endpoint, $startTimestamp, $endTimestamp = null, $type = AnnotationBlock::CLIENT)
    {
        $this->setEndpoint($endpoint);
        $this->setTimestamp('startTimestamp', $startTimestamp);
        $this->setTimestamp('endTimestamp', $endTimestamp, true);
        $this->setValues($type);
    }

    /**
     * Duration for span
     *
     * @return int
     */
    public function getDuration()
    {
        return $this->endTimestamp - $this->startTimestamp;
    }

    /**
     * Timestamp for span
     *
     * @return int
     */
    public function getStartTimestamp()
    {
        return $this->startTimestamp;
    }

    /**
     * AnnotationBlock to array
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            array(
                'endpoint' => $this->endpoint->toArray(),
                'timestamp' => $this->startTimestamp,
                'value' => $this->values[0]
            ),
            array(
                'endpoint' => $this->endpoint->toArray(),
                'timestamp' => $this->endTimestamp,
                'value' => $this->values[1]
            )
        );
    }

    /**
     * Valid type and set annotation types
     *
     * @param $type string
     *
     * @throws \InvalidArgumentException
     */
    protected function setValues($type)
    {
        switch ($type) {
            case static::CLIENT:
                $this->values = array('cs', 'cr');
                break;
            case static::SERVER:
                $this->values = array('sr', 'ss');
                break;
            default:
                throw new \InvalidArgumentException('$type must be TYPE_CLIENT or TYPE_SERVER');
        }
    }

    /**
     * Valid and set endpoint
     *
     * @param $endpoint Endpoint
     *
     * @throws \InvalidArgumentException
     */
    protected function setEndpoint($endpoint)
    {
        if (!($endpoint instanceof Endpoint)) {
            throw new \InvalidArgumentException('$endpoint must be instance of Endpoint');
        }

        $this->endpoint = $endpoint;
    }

    /**
     * Valid and set timestamp
     *
     * @param $field string
     * @param $timestamp int
     * @param $now bool
     *
     * @throws \InvalidArgumentException
     *
     * @return null
     */
    protected function setTimestamp($field, $timestamp, $now = false)
    {
        if ($timestamp === null && $now) {
            $this->{$field} = zipkin_timestamp();
            return null;
        }

        if (!is_zipkin_timestamp($timestamp)) {
            throw new \InvalidArgumentException($field . ' must be generated by zipkin_timestamp()');
        }

        $this->{$field} = $timestamp;
        return null;
    }
}
