
import {
    Customer as ReplicatorCustomer,
    Project as ReplicatorProject,
} from '@frontastic/common/src/js/types/replicator'

import {
    Session as AccountSession,
} from '@frontastic/common/src/js/types/account'

export interface App {
     appId?: string;
     identifier?: string;
     sequence?: string;
     name?: string;
     description?: string;
     configurationSchema?: any /* \StdClass */;
     environment?: string;
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
}

export interface AppRepository {
     app?: string;
     sequence?: string;
}

export interface Context {
     environment?: string;
     customer?: ReplicatorCustomer;
     project?: ReplicatorProject;
     projectConfiguration?: any;
     projectConfigurationSchema?: any;
     locale?: string;
     currency?: string;
     routes?: string[];
     session?: AccountSession;
     featureFlags?: any;
     host?: string;
}

export interface Tastic {
     tasticId?: string;
     tasticType?: string;
     sequence?: string;
     name?: string;
     description?: string;
     configurationSchema?: any /* \StdClass */;
     environment?: string;
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
     isDeleted?: boolean;
}
