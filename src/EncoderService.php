<?php
declare(strict_types=1);

namespace RealPage\JsonApi;

use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Encoder\Encoder;

/**
 * Class EncoderService
 */
class EncoderService
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var array
     */
    protected $encoders = [];

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function getEncoder(string $name = 'default')
    {
        if (!isset($this->encoders[$name])) {
            if ($name === 'default') {
                $config = $this->config;
            } elseif (isset($this->config['encoders'][$name])) {
                $config = $this->config['encoders'][$name];
            } else {
                throw new \Exception(sprintf('No configuration found for %s "%s"', Encoder::class, $name));
            }

            $encoder_options = isset($config['encoder-options']) && is_array($config['encoder-options']) ?
                $config['encoder-options'] :
                [];

            $encoder = Encoder::instance(
                $this->config['schemas']
            );
            $encoder = empty($encoder_options) ? $encoder : $this->makeEncoderOptions($encoder, $encoder_options);

            if (isset($config['jsonapi'])) {
                if (is_array($config['jsonapi'])) {
                    $encoder->withJsonApiVersion($config['jsonapi']);
                } elseif ($config['jsonapi'] === true) {
                    $encoder->withJsonApiVersion(Encoder::JSON_API_VERSION);
                }
            }
            if (isset($config['meta']) && is_array($config['meta'])) {
                $encoder->withMeta($config['meta']);
            }

            $this->encoders[$name] = $encoder;
        }

        return $this->encoders[$name];
    }

    protected function makeEncoderOptions(EncoderInterface $encoder, array $config)
    {
        $options = isset($config['options']) && is_int($config['options']) ?
            $config['options'] :
            0;
        $encoder->withEncodeOptions($options);

        $urlPrefix = isset($config['urlPrefix']) && is_string($config['urlPrefix']) ?
            $config['urlPrefix'] :
            Encoder::DEFAULT_URL_PREFIX;
        $encoder->withUrlPrefix($urlPrefix);

        $depth = isset($config['depth']) && is_int($config['depth']) ?
            $config['depth'] :
            512;
        $encoder->withEncodeDepth($depth);

        return $encoder;
    }
}
