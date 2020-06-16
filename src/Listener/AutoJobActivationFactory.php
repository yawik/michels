<?php

/**
 * Michels
 *
 * @filesource
 * @copyright 2020 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Michels\Listener;

use Interop\Container\ContainerInterface;

/**
 * Factory for \Michels\Listener\AutoJobActivation
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class AutoJobActivationFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): AutoJobActivation {
        $repositories = $container->get('repositories');
        $snaphots     = $repositories->get('Jobs/JobSnapshot');
        $jobs         = $repositories->get('Jobs');
        $app          = $container->get('Application');
        $response     = $app->getResponse();
        $jobEvents = $container->get('Jobs/Events');

        return new AutoJobActivation($jobs, $snaphots, $response, $jobEvents);
    }
}
