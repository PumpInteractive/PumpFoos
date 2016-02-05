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

use CL\Slack\Payload\GroupsSetTopicPayload;
use CL\Slack\Payload\PayloadInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsSetTopicPayloadTest extends AbstractPayloadTestCase
{
    /**
     * @inheritdoc
     */
    protected function createPayload()
    {
        $payload = new GroupsSetTopicPayload();
        $payload->setGroupId('G1234567');
        $payload->setTopic('new_topic');

        return $payload;
    }

    /**
     * @inheritdoc
     *
     * @param GroupsSetTopicPayload $payload
     */
    protected function getExpectedPayloadData(PayloadInterface $payload)
    {
        return [
            'channel' => $payload->getGroupId(),
            'topic' => $payload->getTopic(),
        ];
    }
}
