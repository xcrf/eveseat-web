<div class="card">
  <div class="card-header">
    <h3 class="card-title">{{ trans_choice('web::seat.starbase', 2) }}</h3>
    <div class="card-tools">
      <div class="input-group input-group-sm">
        @include('web::components.jobs.buttons.update', ['type' => 'corporation', 'entity' => $corporation->corporation_id, 'job' => 'corporation.starbases', 'label' => trans('web::seat.update_starbases')])
      </div>
    </div>
  </div>
  <div class="card-body">

    <table class="table datatable compact table-condensed table-hover">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.state', 1) }}</th>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans_choice('web::seat.location', 1) }}</th>
        <th>
          @include('web::partials.type', ['type_id' => 4051, 'type_name' => trans('web::seat.fuel_level')])
        </th>
        <th>{{ trans_choice('web::seat.offline', 1) }}</th>
        <th data-orderable="false"></th>
      </tr>
      </thead>
      <tbody>

      @foreach($starbases as $starbase)

        <tr>
          <td data-order="{{ $starbase->updated_at }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="Last Update: {{ $starbase->updated_at }}">
                <span class="badge
                  @if($starbase->state == 'online')
                  badge-success
                  @elseif($starbase->state == 'unanchored' || $starbase->state == 'offline')
                  badge-danger
                  @else
                  badge-warning
                  @endif">
                  {{ ucfirst($starbase->state) }}
                </span>
              </span>
          </td>
          <td data-order="{{ $starbase->type->typeName }}">
              <span data-toggle="tooltip"
                    title="" data-original-title="{{ $starbase->type->typeName }}">
                @include('web::partials.type', ['type_id' => $starbase->type_id, 'type_name' => $starbase->type->typeName])
              </span>
            @if($starbase->solar_system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->solar_system->sovereignty->corporation_id == $starbase->corporation_id)

              @if(carbon('now')->diffInHours(carbon('now')->addHours(optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0/ ceil($starbase->baseFuelUsage * 0.75))) < 24)
                <span class="text-red float-right"><i>{{ trans('web::seat.low_fuel') }} !</i></span>
              @elseif(carbon('now')->diffInHours(carbon('now')->addHours(optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0 / ceil($starbase->baseFuelUsage * 0.75))) < 72)
                <span class="text-yellow float-right"><i>{{ trans('web::seat.low_fuel') }}</i></span>
              @endif

            @else

              @if(carbon('now')->diffInHours(carbon('now')->addHours(optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0 / $starbase->baseFuelUsage)) < 24)
                <span class="text-red float-right"><i>{{ trans('web::seat.low_fuel') }} !</i></span>
              @elseif(carbon('now')->diffInHours(carbon('now')->addHours(optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0 / $starbase->baseFuelUsage)) < 72)
                <span class="text-yellow float-right"><i>{{ trans('web::seat.low_fuel') }}</i></span>
              @endif

            @endif
          </td>
          <td>
            <b>{{ $starbase->moon->name }}</b>
            <span class="
                @if($starbase->moon->security >= 0.5)
                    text-green
                  @elseif($starbase->moon->security < 0.5 && $starbase->moon->security > 0.0)
                    text-warning
                  @else
                    text-red
                  @endif">
                <i>({{ round($starbase->moon->security,  2) }})</i>
              </span>
          </td>
          <td data-order="{{ 100 * (($starbase->fuelBlocks * 5) / $starbase->type->capacity) }}">
            <div class="progress">
              <div class="progress-bar" role="progressbar"
                   aria-valuenow="{{ 100 * ((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) * 5 / $starbase->type->capacity) }}" aria-valuemin="0"
                   aria-valuemax="100"
                   style="width: {{ 100 * ((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) * 5 / $starbase->type->capacity) }}%">
              </div>
            </div>
          </td>
          <td data-order="
          @if($starbase->solar_system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->solar_system->sovereignty->corporation_id == $starbase->corporation_id)
          {{ carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / ceil($starbase->baseFuelUsage * 0.75)) }}
          @else
          {{ carbon('now')->addHours($starbase->fuelBlocks / $starbase->baseFuelUsage)  }}
          @endif
                  ">
            @if($starbase->solar_system->sovereignty->alliance_id == $sheet->alliance_id || $starbase->solar_system->sovereignty->corporation_id == $starbase->corporation_id)
              {{
                carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / ceil($starbase->baseFuelUsage * 0.75))
                  ->diffForHumans()
              }}
            @else
              {{
                carbon('now')->addHours((optional($starbase->fuelBays->whereIn('type_id', [4051, 4246, 4247, 4312, 36945]))->first()->quantity ?? 0) / $starbase->baseFuelUsage)
                  ->diffForHumans()
              }}
            @endif
          </td>
          <td>
            <a href="#starbaseDetail{{ $starbase->starbase_id }}">Detail</a>
          </td>
        </tr>

      @endforeach

      </tbody>
    </table>

  </div>
</div>
