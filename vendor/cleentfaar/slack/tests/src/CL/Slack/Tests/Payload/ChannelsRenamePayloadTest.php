<?php

/*
 * This file is part of the Slack API library.
 *
 * (c) Cas Leentfaar <info@casleentfaar.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CL\Slack\Tests\Payload;

use CL\Slack\Payload\ChannelsRenamePayload;
use CL\Slack\Payload\PayloadInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class ChannelsRenamePayloadTest extends AbstractPayloadTestCase
{
    /**
     * @inheritdoc
     */
    protected function createPayload()
    {
        $payload = new ChannelsRenamePayload();
        $payload->setChannelId('C1234567');
        $payload->setName('new_name');

        return $payload;
    }

    /**
     * @inheritdoc
     *
     * @param ChannelsRenamePayload $payload
     */
    protected function getExpectedPayloadData(PayloadInterface $payload)
    {
        return [
            'channel' => $payload->getChannelId(),
            'name' => $payload->getName(),
        ];
    }
}
