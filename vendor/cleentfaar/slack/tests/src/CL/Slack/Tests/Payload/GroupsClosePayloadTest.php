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

use CL\Slack\Payload\GroupsClosePayload;
use CL\Slack\Payload\PayloadInterface;

/**
 * @author Cas Leentfaar <info@casleentfaar.com>
 */
class GroupsClosePayloadTest extends AbstractPayloadTestCase
{
    /**
     * @inheritdoc
     */
    protected function createPayload()
    {
        $payload = new GroupsClosePayload();
        $payload->setGroupId('G1234567');

        return $payload;
    }

    /**
     * @inheritdoc
     *
     * @param GroupsClosePayload $payload
     */
    protected function getExpectedPayloadData(PayloadInterface $payload)
    {
        return [
            'channel' => $payload->getGroupId(),
        ];
    }
}
