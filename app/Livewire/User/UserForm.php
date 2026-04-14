<?php
namespace App\Livewire\User;

use App\Models\Contacts;
use App\Models\Locations;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Support\Facades\Redirect;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('User Form')]
class UserForm extends Component
{

    public int $id;
    public string $name;
    public string $password;
    public int $contact_id;
    public bool $inactive;
    public int $location_id;
    public bool $locked_location;
    public bool $date_enabled;
    public bool $logs_disabled;
    public $trans_date;
    public $employees    = [];
    public $locationList = [];
    private $userServices;
    public function boot(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }
    public function mount($id = null)
    {
        $this->employees = Contacts::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->where('TYPE', '2')
            ->get();

        $this->locationList = Locations::query()
            ->select(['ID', 'NAME'])
            ->where('INACTIVE', '0')
            ->get();

        if (is_numeric($id)) {

            $user = User::where('ID', $id)->first();

            if ($user) {
                $this->id              = $user->id;
                $this->name            = $user->name;
                $this->password        = '';
                $this->contact_id      = $user->contact_id ? $user->contact_id : 0;
                $this->inactive        = $user->inactive;
                $this->location_id     = $user->location_id ? $user->location_id : 0;
                $this->trans_date      = $user->trans_date ?? null;
                $this->locked_location = $user->locked_location ?? false;
                $this->date_enabled    = $user->date_enabled ?? false;
                $this->logs_disabled   = $user->logs_disabled ?? false;
                return;
            }

            $errorMessage = 'Error occurred: Record not found. ';
            return Redirect::route('maintenancesettingsusers')->with('error', $errorMessage);
        }

        $this->id              = 0;
        $this->name            = '';
        $this->password        = '';
        $this->contact_id      = 0;
        $this->inactive        = false;
        $this->location_id     = 0;
        $this->trans_date      = '';
        $this->locked_location = false;
        $this->date_enabled    = false;
        $this->logs_disabled   = false;
    }

    public function save()
    {
        if ($this->id === 0) {
            $this->validate(
                [
                    'name'     => 'required|max:10|unique:users,name,' . $this->id,
                    'password' => 'required|min:3|max:16|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                ],
                [],
                [
                    'name'     => 'Username',
                    'Password' => 'Password',

                ]
            );
        } else {

            if ($this->password) {
                $this->validate(
                    [
                        'name'     => 'required|max:10|unique:users,name,' . $this->id,
                        'password' => 'required|min:3|max:16|regex:/^(?=.*[A-Za-z])(?=.*\d).+$/',
                    ],
                    [],
                    [
                        'name'     => 'Username',
                        'Password' => 'Password',

                    ]
                );
            } else {
                $this->validate(
                    [
                        'name' => 'required|max:10|unique:users,name,' . $this->id,

                    ],
                    [],
                    [
                        'name' => 'Username',

                    ]
                );
            }
        }

        try {
            if ($this->id === 0) {
                $this->id = $this->userServices->Store(
                    $this->name,
                    $this->password,
                    $this->contact_id,
                    $this->inactive,
                    $this->location_id,
                    $this->trans_date ?? '',
                    $this->locked_location,
                    $this->date_enabled,
                    $this->logs_disabled
                );
                return Redirect::route('maintenancesettingsusers_edit', ['id' => $this->id])->with('message', 'Successfully created.');
            } else {
                $this->userServices->Update(
                    $this->id,
                    $this->name,
                    $this->password,
                    $this->contact_id,
                    $this->inactive,
                    $this->location_id,
                    $this->trans_date ?? '',
                    $this->locked_location,
                    $this->date_enabled,
                    $this->logs_disabled
                );
                session()->flash('message', 'Successfully updated.');
            }
        } catch (\Exception $e) {
            $errorMessage = 'Error occurred: ' . $e->getMessage();
            session()->flash('error', $errorMessage);
        }
    }

    public function render()
    {
        return view('livewire.user.user-form');
    }
}
