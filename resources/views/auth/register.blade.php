<x-guest-layout>
    <div class="login-container">
        <ul class="nav nav-tabs" role="tablist">
            @if (Route::has('login'))
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('login') }}" role="tab">Connexion</a>
                </li>
            @endif
            <li class="nav-item">
                <a class="nav-link active" id="signup-tab" data-bs-toggle="tab" href="#signup-section"
                    role="tab">Inscription</a>
            </li>
        </ul>

        <div id="signup-section" class="active">
            <div class="stepper">
                <div class="step active" data-step="1">1</div>
                <div class="step" data-step="2">2</div>
                <div class="step" data-step="3">3</div>
                <div class="step" data-step="4">4</div>
            </div>

            <form id="signup-form" method="POST" action="{{ route('register') }}" class="needs-validation" novalidate>
                @csrf

                @if ($errors->any())
                    <div class="alert alert-danger mb-3">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Étape 1: Informations personnelles -->
                <div class="step-content active" data-step="1">
                    <h4 class="mb-4">Informations personnelles</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Prénom</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nom</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    <input type="text" class="form-control @error('surname') is-invalid @enderror"
                                        name="surname" value="{{ old('surname') }}">
                                    @error('surname')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                name="password" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Confirmer le mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control" name="password_confirmation" required>
                            <button type="button" class="btn btn-outline-secondary toggle-password">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Âge</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                            <input type="number" class="form-control @error('age') is-invalid @enderror" name="age"
                                value="{{ old('age') }}" required>
                            @error('age')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="gender" value="male"
                                    class="selectgroup-input @error('gender') is-invalid @enderror" required
                                    {{ old('gender') == 'male' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Masculin</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="gender" value="female"
                                    class="selectgroup-input @error('gender') is-invalid @enderror"
                                    {{ old('gender') == 'female' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Féminin</span>
                            </label>
                            @error('gender')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Lieu de résidence</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-home"></i></span>
                            <input type="text" class="form-control @error('residence') is-invalid @enderror"
                                name="residence" value="{{ old('residence') }}" required>
                            @error('residence')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Profession</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control @error('profession') is-invalid @enderror"
                                name="profession" value="{{ old('profession') }}" required>
                            @error('profession')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Profession détaillée</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                            <input type="text"
                                class="form-control @error('profession_details') is-invalid @enderror"
                                name="profession_details" value="{{ old('profession_details') }}">
                            @error('profession_details')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Téléphone</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            <input type="tel" class="form-control @error('phoneNumber') is-invalid @enderror"
                                name="phoneNumber" value="{{ old('phoneNumber') }}" pattern="[0-9]{9}" required>
                            @error('phoneNumber')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Étape 2: Informations spirituelles -->
                <div class="step-content" data-step="2">
                    <h4 class="mb-4">Informations spirituelles</h4>

                    <div class="form-group">
                        <label>Baptême</label>
                        <div class="selectgroup selectgroup-pills">
                            <label class="selectgroup-item">
                                <input type="checkbox" name="baptism_type[]" value="water"
                                    class="selectgroup-input"
                                    {{ old('baptism_type') && in_array('water', old('baptism_type')) ? 'checked' : '' }}>
                                <span class="selectgroup-button">Eau</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="checkbox" name="baptism_type[]" value="holy-spirit"
                                    class="selectgroup-input"
                                    {{ old('baptism_type') && in_array('holy-spirit', old('baptism_type')) ? 'checked' : '' }}>
                                <span class="selectgroup-button">Saint-Esprit</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Marqueur nominal</label>
                        <select class="form-control @error('baptism_nominalMaker') is-invalid @enderror"
                            name="baptism_nominalMaker">
                            <option value="conqueror"
                                {{ old('baptism_nominalMaker') == 'conqueror' ? 'selected' : '' }}>
                                Vainqueurs</option>
                            <option value="messengers"
                                {{ old('baptism_nominalMaker') == 'messengers' ? 'selected' : '' }}>Messagers</option>
                            <option value="warriors"
                                {{ old('baptism_nominalMaker') == 'warriors' ? 'selected' : '' }}>
                                Guerriers</option>
                            <option value="female-warriors"
                                {{ old('baptism_nominalMaker') == 'female-warriors' ? 'selected' : '' }}>
                                Guerrières</option>
                            <option value="soldat" {{ old('baptism_nominalMaker') == 'soldat' ? 'selected' : '' }}>
                                Soldat
                            </option>
                        </select>
                        @error('baptism_nominalMaker')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Manifestation des Dons du Saint-Esprit</label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" name="baptism_hasHolySpirit" class="form-check-input"
                                value="1" {{ old('baptism_hasHolySpirit') ? 'checked' : '' }}>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Niveau ministériel</label>
                        <select class="form-control @error('baptism_ministerialLevel') is-invalid @enderror"
                            name="baptism_ministerialLevel">
                            <option value="pastor"
                                {{ old('baptism_ministerialLevel') == 'pastor' ? 'selected' : '' }}>
                                Pasteur</option>
                            <option value="predictor"
                                {{ old('baptism_ministerialLevel') == 'predictor' ? 'selected' : '' }}>Prédicateur
                            </option>
                            <option value="elder" {{ old('baptism_ministerialLevel') == 'elder' ? 'selected' : '' }}>
                                Ancien</option>
                            <option value="worker"
                                {{ old('baptism_ministerialLevel') == 'worker' ? 'selected' : '' }}>
                                Ouvrier</option>
                            <option value="deacon"
                                {{ old('baptism_ministerialLevel') == 'deacon' ? 'selected' : '' }}>
                                Diacre</option>
                        </select>
                        @error('baptism_ministerialLevel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Étape 3: Antécédents -->
                <div class="step-content" data-step="3">
                    <h4 class="mb-4">Antécédents et historique</h4>

                    <div class="form-group">
                        <label>Antécédent</label>
                        <textarea class="form-control @error('antecedent') is-invalid @enderror" rows="4" name="antecedent">{{ old('antecedent') }}</textarea>
                        @error('antecedent')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Date d'arrivée</label>
                        <input type="date" class="form-control @error('arrivalDate') is-invalid @enderror"
                            name="arrivalDate" value="{{ old('arrivalDate') }}">
                        @error('arrivalDate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label style="margin-bottom: 8px;">Appréciation</label>
                        <div class="selectgroup w-100">
                            <label class="selectgroup-item">
                                <input type="radio" name="isActive" value="1" class="selectgroup-input"
                                    {{ old('isActive', '1') == '1' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Actif</span>
                            </label>
                            <label class="selectgroup-item">
                                <input type="radio" name="isActive" value="0" class="selectgroup-input"
                                    {{ old('isActive') == '0' ? 'checked' : '' }}>
                                <span class="selectgroup-button">Inactif</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Étape 4: Informations familiales -->
                <div class="step-content" data-step="4">
                    <h4 class="mb-4">Informations familiales et santé</h4>

                    <div class="form-group">
                        <label>État matrimonial</label>
                        <select class="form-control @error('maritalStatus') is-invalid @enderror"
                            name="maritalStatus">
                            <option value="married" {{ old('maritalStatus') == 'married' ? 'selected' : '' }}>Marié
                            </option>
                            <option value="single" {{ old('maritalStatus') == 'single' ? 'selected' : '' }}>
                                Célibataire
                            </option>
                            <option value="concubinage" {{ old('maritalStatus') == 'concubinage' ? 'selected' : '' }}>
                                En
                                concubinage</option>
                        </select>
                        @error('maritalStatus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Nombre d'enfants</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-users"></i></span>
                            <input type="number"
                                class="form-control @error('numberOfChildren') is-invalid @enderror" min="0"
                                name="numberOfChildren" value="{{ old('numberOfChildren', 0) }}">
                            @error('numberOfChildren')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Maladies graves</label>
                        <textarea class="form-control @error('seriousIllnesses') is-invalid @enderror" rows="4"
                            name="seriousIllnesses">{{ old('seriousIllnesses') }}</textarea>
                        @error('seriousIllnesses')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Niveau spirituel</label>
                        <input type="range" class="form-range" min="0" max="6"
                            name="baptism_spiritualLevel" value="{{ old('baptism_spiritualLevel', 0) }}">
                    </div>
                </div>

                <div class="btn-navigation">
                    <button type="button" class="btn btn-secondary" id="prevBtn"
                        style="display: none;">Précédent</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Suivant</button>
                    <button type="submit" class="btn btn-success" id="submitBtn"
                        style="display: none;">Terminer</button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
