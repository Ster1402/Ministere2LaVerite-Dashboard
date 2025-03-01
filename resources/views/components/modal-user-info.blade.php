@php use App\Models\Baptism;use App\Models\User; @endphp
@props(['id', 'action' => ''])

<x-modal :id="$id" :action="$action">
    <section class="section">
        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>1-/ Informations personnelles</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Nom de famille</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input id="name" name="name" value="{{ old('name') ?? $user?->name }}" type="text"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="surname">Prénom</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input id="surname" name="surname" value="{{ old('surname') ?? $user?->surname }}"
                                           type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Adresse mail</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input id="email" name="email" type="email"
                                           value="{{ old('email') ?? $user?->email }}"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="dateOfBirth">Date de naissance</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    </div>
                                    <input id="dateOfBirth" name="dateOfBirth"
                                           value="{{ old('dateOfBirth') ?? $user?->dateOfBirth?->format('Y-m-d') }}"
                                           type="date"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="gender" class="form-label">Genre</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="gender" value="male" class="selectgroup-input"
                                            {{ (old('gender') ?? $user?->gender) == 'male' ? 'checked' : '' }}>
                                        <span class="selectgroup-button">Homme</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="gender" value="female" class="selectgroup-input"
                                            {{ (old('gender') ?? $user?->gender) == 'female' ? 'checked' : '' }}>
                                        <span class="selectgroup-button">Femme</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="residence">Lieu de résidence</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-home"></i>
                                        </div>
                                    </div>
                                    <input id="residence" name="residence"
                                           value="{{ old('residence') ?? $user?->residence }}" type="text"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phoneNumber">Numéro de téléphone</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </div>
                                    </div>
                                    <input id="phoneNumber" name="phoneNumber"
                                           value="{{ old('phoneNumber') ?? $user?->phoneNumber }}" type="text"
                                           class="form-control phone-number">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="profession">Occupation</label>
                                <select id="profession" name="profession"
                                        class="form-control select2" style="width:300px">
                                    @foreach(User::professions() as $profession)
                                        <option
                                            value="{{ $profession->name }}" {{ (old('profession') == $profession->name || $user?->profession == $profession->name) ? 'selected' : ''}}>
                                            {{ $profession->displayName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="profession_details">Détails sur l'occupation</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-user-graduate"></i>
                                        </div>
                                    </div>
                                    <input id="profession_details"
                                           value="{{ old('profession_details') ?? $user?->profession_details }}"
                                           name="profession_details" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Est discipliné</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="isDisciplined"
                                               value="{{ true }}"
                                               class="selectgroup-input"
                                            {{ (old('isDisciplined') || ($user?->isDisciplined === true)) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">Oui</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="isDisciplined"
                                               value="{{ false }}"
                                               {{ ($user?->isDisciplined === false) ? 'checked' : '' }}
                                               {{ (old('isDisciplined') === false) ? 'checked' : '' }}
                                               class="selectgroup-input">
                                        <span class="selectgroup-button">Non</span>
                                    </label>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="password">Mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input id="password" autocomplete="off" name="password" type="password"
                                           class="form-control pwstrength"
                                           data-indicator="pwindicator">
                                </div>
                                <div id="pwindicator" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Confirmation du mot de passe</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <div class="input-group-text">
                                            <i class="fas fa-lock"></i>
                                        </div>
                                    </div>
                                    <input id="password_confirmation" autocomplete="off" name="password_confirmation"
                                           type="password"
                                           class="form-control pwstrength" data-indicator="pwindicator2">
                                </div>
                                <div id="pwindicator2" class="pwindicator">
                                    <div class="bar"></div>
                                    <div class="label"></div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="comment">Commentaire</label>
                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 col-md-7">
                                        <textarea id="comment" name="comment"
                                                  class="summernote-simple">{{ old('comment') ?? $user?->comment }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>2-/ Informations de Baptême</h4>
                            </div>
                            <div class="card-body">
                                <div class="form-group">

                                    <div class="form-group">
                                        <label class="form-label">Baptême </label>
                                        <div class="selectgroup selectgroup-pills">
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="baptism_type[]" value="water"
                                                       class="selectgroup-input"
                                                    {{ (old('baptism_type') ?? $user?->baptisms()->where('type', 'LIKE', '%water%')->exists()) ? 'checked' : ''}}>
                                                <span class="selectgroup-button">Eau</span>
                                            </label>
                                            <label class="selectgroup-item">
                                                <input type="checkbox" name="baptism_type[]" value="holy-spirit"
                                                       class="selectgroup-input" {{ (old('baptism_type') ?? $user?->baptisms()->where('type', 'LIKE', '%holy-spirit%')->exists()) ? 'checked' : ''}}>
                                                <span class="selectgroup-button">Saint-Esprit</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="baptism_date_water">Date du baptême eau</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input id="baptism_date_water" name="baptism_date_water"
                                                   value="{{ old('baptism_date_water') ?? $user?->baptisms?->first()?->date_water?->format('Y-m-d') }}"
                                                   type="date"
                                                   class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="baptism_date_holy_spirit">Date du baptême Saint-Esprit</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input id="baptism_date_holy_spirit" name="baptism_date_holy_spirit"
                                                   value="{{ old('baptism_date_holy_spirit') ?? $user?->baptisms?->first()?->date_holy_spirit?->format('Y-m-d') }}"
                                                   type="date"
                                                   class="form-control">
                                        </div>
                                    </div>

{{--                                    <div class="form-group">--}}
{{--                                        <label for="baptism_date_latest">Date du dernier baptême</label>--}}
{{--                                        <div class="input-group">--}}
{{--                                            <div class="input-group-prepend">--}}
{{--                                                <div class="input-group-text">--}}
{{--                                                    <i class="fas fa-user"></i>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                            <input id="baptism_date_latest" name="baptism_on"--}}
{{--                                                   value="{{ old('baptism_date_latest') ?? $user?->baptisms?->first()?->date_latest?->format('Y-m-d') }}"--}}
{{--                                                   type="date"--}}
{{--                                                   class="form-control">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}

                                    <div class="form-group">
                                        <label for="baptism_nominalMaker">Marqueur nominal</label>
                                        <select id="baptism_nominalMaker" name="baptism_nominalMaker"
                                                class="form-control select2" style="width:300px">
                                            <option
                                                value="conqueror" {{ (old('baptism_nominalMaker') === 'conqueror' || $user?->baptisms?->contains('nominalMaker','conqueror')) ? 'selected' : ''}}>
                                                Conquérant(e)
                                            </option>
                                            <option
                                                value="messenger" {{ (old('baptism_nominalMaker') === 'messengers' || $user?->baptisms?->contains('nominalMaker', 'messengers')) ? 'selected' : ''}}>
                                                Messager / Messagère
                                            </option>
                                            <option
                                                value="warriors" {{ (old('baptism_nominalMaker') === 'warriors' || $user?->baptisms?->contains('nominalMaker','warriors')) ? 'selected' : ''}}>
                                                Guerrier / Guerrière
                                            </option>
                                            <option
                                                value="soldat" {{ (old('baptism_nominalMaker') === 'soldat' || $user?->baptisms?->contains('nominalMaker', 'soldat')) ? 'selected' : ''}}>
                                                Soldat(e)
                                            </option>
                                        </select>
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label class="control-label">Manifestation des Dons du Saint-Esprit</label>
                                    <div class="selectgroup w-100">
                                        <label class="selectgroup-item">
                                            <input type="radio" name="baptism_hasHolySpirit"
                                                   value="{{ true }}"
                                                   class="selectgroup-input"
                                                {{ (old('baptism_hasHolySpirit') || $user?->baptisms->contains('hasHolySpirit', true)) ? 'checked' : '' }}>
                                            <span class="selectgroup-button">Oui</span>
                                        </label>
                                        <label class="selectgroup-item">
                                            <input type="radio" name="baptism_hasHolySpirit"
                                                   value="{{ false }}"
                                                   {{ ($user?->baptisms->contains('hasHolySpirit', false)) ? 'checked' : '' }}
                                                   {{ (old('baptism_hasHolySpirit') === false) ? 'checked' : '' }}
                                                   class="selectgroup-input">
                                            <span class="selectgroup-button">Non</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="baptism_ministerialLevel">Niveau Sacerdotale</label>
                                    <select id="baptism_ministerialLevel" name="baptism_ministerialLevel"
                                            class="form-control select2" style="width:300px">
                                        @foreach(Baptism::sacerdotalLevels() as $sacerdotalLevel)
                                            <option
                                                value="{{ $sacerdotalLevel->name }}" {{ $user?->baptisms?->contains('ministerialLevel', $sacerdotalLevel->name) ? 'selected' : '' }} >
                                                {{ $sacerdotalLevel->displayName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>3-/ Informations complémentaire</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="antecedent">Antécédent</label>
                                <div class="form-group row mb-4">
                                    <div class="col-sm-12 col-md-7">
                                        <textarea id="antecedent" name="antecedent"
                                                  class="summernote-simple">{{ old('antecedent') ?? $user?->antecedent }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="arrivalDate">Date d'arrivée</label>
                                <input id="arrivalDate" name="arrivalDate"
                                       value="{{ old('arrivalDate') ?? $user?->arrivalDate?->format('Y-m-d') }}"
                                       type="date" class="form-control datepicker">
                            </div>

                            @if($user?->arrivalDate)
                                <div class="form-group">
                                    <label>Durée dans l'église</label>
                                    <div class="col-9">
                                        <p class="mb-0">{{ $user->arrivalDate->diffForHumans() }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="form-group">
                                <label for="isActive" class="form-label">Appréciation</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="isActive"
                                               value="{{ true }}"
                                               class="selectgroup-input"
                                            {{ (old('isActive') || $user?->isActive) ? 'checked' : '' }}>
                                        <span class="selectgroup-button">Actif</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="isActive"
                                               value="{{ false }}"
                                               {{ ($user?->isActive === false) ? 'checked' : '' }}
                                               {{ (old('isActive') === false) ? 'checked' : '' }}
                                               class="selectgroup-input">
                                        <span class="selectgroup-button">Inactif</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <h4>4-/ Informations matrimonial</h4>
                        </div>
                        <div class="card-body">
                            <div class="form-group">

                                <div class="form-group">
                                    <label for="maritalStatus">État matrimonial</label>
                                    <select id="maritalStatus" name="maritalStatus" class="form-control select2 col-7"
                                            style="width:300px">
                                        <option
                                            value="married" {{ (old('maritalStatus') ?? $user?->maritalStatus) == 'married' ? 'selected' : '' }} >
                                            Marié(e)
                                        </option>
                                        <option
                                            value="divorced" {{ (old('maritalStatus') ?? $user?->maritalStatus) == 'divorced' ? 'selected' : '' }} >
                                            Divorcé(e)
                                        </option>
                                        <option
                                            value="widower" {{ (old('maritalStatus') ?? $user?->maritalStatus) == 'widower' ? 'selected' : '' }} >
                                            Veuf(ve)
                                        </option>
                                        <option
                                            value="single" {{ (old('maritalStatus') ?? $user?->maritalStatus )== 'single' ? 'selected' : '' }} >
                                            Célibataire
                                        </option>
                                        <option
                                            value="concubinage" {{ (old('maritalStatus') ?? $user?->maritalStatus) == 'concubinage' ? 'selected' : '' }} >
                                            En concubinage
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="numberOfChildren">Nombre d'enfants</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        </div>
                                        <input id="numberOfChildren" name="numberOfChildren" type="number"
                                               value="{{ (old('numberOfChildren') ?? $user?->numberOfChildren) ?? 0 }}"
                                               class="form-control">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="control-label">Femme stérile ?</div>
                                    <label for="sterileWoman" class="custom-switch mt-2">
                                        <input id="sterileWoman" type="checkbox" name="sterileWoman"
                                               value="{{ old('sterileWoman') ?? $user?->sterileWoman }}"
                                               class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span class="custom-switch-description"> Oui ou Non ??</span>
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="seriousIllnesses">Maladies graves</label>
                                    <div class="form-group row mb-4">
                                        <div class="col-sm-12 col-md-7">
                                            <textarea id="seriousIllnesses" name="seriousIllnesses"
                                                      class="summernote-simple">{{ old('seriousIllnesses') ?? $user?->seriousIllnesses }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="baptism_spiritualLevel">Niveau d'enseignement</label> <br>
                                    <input type="range"
                                           name="baptism_spiritualLevel" min="0" max="6"
                                           value="{{ (old('baptism_spiritualLevel') ?? $user?->baptisms?->first()?->spiritualLevel) }}"
                                           class="form-range"
                                           id="baptism_spiritualLevel"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    {{ $slot }}
</x-modal>
