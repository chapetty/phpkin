<?php
namespace whitemerry\phpkin;

class Endpoint
{
    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $ip;

    /**
     * @var string
     */
    protected $port;

    /**
     * Endpoint constructor.
     *
     * @param $serviceName string Service name
     * @param $ip string IP address
     * @param $port string Port
     */
    public function __construct($serviceName, $ip, $port = '80')
    {
        $this->setServiceName($serviceName);
        $this->setIp($ip);
        $this->setPort((string) $port);
    }

    /**
     * Converts Endpoint to array
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'serviceName' => $this->serviceName,
            'ipv4' => $this->ip,
            'port' => $this->port
        ];
    }

    /**
     * Valid and set serviceName
     *
     * @param $serviceName string
     *
     * @throws \InvalidArgumentException
     */
    protected function setServiceName($serviceName)
    {
        if (!is_string($serviceName)) {
            throw new \InvalidArgumentException('The service name must be a string');
        }

        $this->serviceName = $serviceName;
    }

    /**
     * Valid and set ip
     *
     * @param $ip string
     *
     * @throws \InvalidArgumentException
     */
    protected function setIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP) === false) {
            throw new \InvalidArgumentException('The IP address must be correct');
        }

        $this->ip = $ip;
    }

    /**
     * Valid and set port
     *
     * @param $port int
     *
     * @throws \InvalidArgumentException
     */
    protected function setPort($port)
    {
        if (ctype_digit($port) === false) {
            throw new \InvalidArgumentException('Port can only contain digits');
        }

        $this->port = $port;
    }
}