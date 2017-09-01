function module(name, testEnvironment, executeNow) {
	var module, moduleFns;
	var currentModule = config.currentModule;

	if (arguments.length === 2) {
		if (objectType(testEnvironment) === "function") {
			executeNow = testEnvironment;
			testEnvironment = undefined;
		}
	}

	module = createModule();

	moduleFns = {
		before: setHook(module, "before"),
		beforeEach: setHook(module, "beforeEach"),
		afterEach: setHook(module, "afterEach"),
		after: setHook(module, "after")
	};

	if (objectType(executeNow) === "function") {
		config.moduleStack.push(module);
		setCurrentModule(module);
		executeNow.call(module.testEnvironment, moduleFns);
		config.moduleStack.pop();
		module = module.parentModule || currentModule;
	}

	setCurrentModule(module);

	function createModule() {
		var parentModule = config.moduleStack.length ? config.moduleStack.slice(-1)[0] : null;
		var moduleName = parentModule !== null ? [parentModule.name, name].join(" > ") : name;
		var module = {
			name: moduleName,
			parentModule: parentModule,
			tests: [],
			moduleId: generateHash(moduleName),
			testsRun: 0,
			childModules: []
		};

		var env = {};
		if (parentModule) {
			parentModule.childModules.push(module);
			extend(env, parentModule.testEnvironment);
			delete env.beforeEach;
			delete env.afterEach;
		}
		extend(env, testEnvironment);
		module.testEnvironment = env;

		config.modules.push(module);
		return module;
	}

	function setCurrentModule(module) {
		config.currentModule = module;
	}
}