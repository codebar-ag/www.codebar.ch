<?php

namespace App\Console\Commands;

use App\Enums\LocaleEnum;
use App\Jobs\Network\SendNetworkInviteJob;
use App\Models\NetworkUser;
use Illuminate\Console\Command;

class SendNetworkInvitesCommand extends Command
{
    protected $signature = 'network:invite
        {--locale= : Language of the invitation (de or en)}
        {--email= : Only invite this email address}';

    protected $description = 'Invite network users via a signed link (valid 96 hours) to manage and publish their profile';

    public function handle(): int
    {
        $locale = match ($this->option('locale')) {
            'de' => LocaleEnum::DE,
            'en' => LocaleEnum::EN,
            default => null,
        };

        if (! $locale) {
            $this->error('Please pass --locale=de or --locale=en.');

            return self::FAILURE;
        }

        $users = NetworkUser::query()
            ->whereNotNull('email')
            ->when($this->option('email'), fn ($query, string $email) => $query->where('email', $email))
            ->orderBy('name')
            ->get();

        if ($users->isEmpty()) {
            $this->warn('No network users with an email address found.');

            return self::SUCCESS;
        }

        $this->table(
            ['Name', 'Email', 'Company'],
            $users->map(fn (NetworkUser $user): array => [$user->name, $user->email, $user->network_key])->all(),
        );

        if (! $this->confirm(sprintf('Send %d invitation(s) in %s?', $users->count(), $locale->value))) {
            $this->info('Aborted — nothing sent.');

            return self::SUCCESS;
        }

        foreach ($users as $user) {
            if ($user->email === null) {
                continue;
            }

            SendNetworkInviteJob::dispatch($user->email, $locale->value);
        }

        $this->info(sprintf('%d invitation(s) queued.', $users->count()));

        return self::SUCCESS;
    }
}
