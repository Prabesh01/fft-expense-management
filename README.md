Project is live at  [fft.cote.ws](https://fft.cote.ws)!

# Run (Dev):

- `php artisan migrate`
- `php artisan db:seed`
- `php artisan queue:work`
- `php artisan serve`

# Test Users:
- `manager+fft@cote.ws`:`password`
- `john+fft@cote.ws`: `password`
- `jane+fft@cote.ws`: `password`

# Production Deploy
_Check `deploy/` dir for nginx conf, systemd service and cronjob used for deploying in production_
