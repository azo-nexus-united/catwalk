
import {
    Configuration as FrontendCellConfiguration,
} from 'cell'

import {
    FacetDefinition as ProductProductApiFacetDefinition,
} from '@frontastic/common/src/js/types/product/productapi'

import {
    Configuration as FrontendRegionConfiguration,
} from 'region'

import {
    Context as ApiCoreContext,
} from '../apicore'

import {
    Configuration as FrontendTasticConfiguration,
} from 'tastic'

export interface Cell {
     cellId?: string;
     configuration?: FrontendCellConfiguration;
     customConfiguration?: null | any /* \stdClass */;
     tastics?: Tastic[];
}

export interface Configuration {
     mobile?: boolean;
     tablet?: boolean;
     desktop?: boolean;
}

export interface Facet extends ProductProductApiFacetDefinition {
     facetId?: string;
     sequence?: string;
     sort?: number;
     isEnabled?: boolean;
     label?: null | any;
     urlIdentifier?: string;
     facetOptions?: any;
     metaData?: any /* \Frontastic\Catwalk\FrontendBundle\Domain\MetaData */;
     isDeleted?: boolean;
}

export interface Layout {
     layoutId?: string;
     sequence?: string;
     name?: string;
     description?: string;
     image?: string;
     regions?: string[];
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
}

export interface MasterPageMatcherRules {
     rulesId?: string;
     rules?: any;
     sequence?: string;
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
}

export interface Node {
     nodeId?: string;
     isMaster?: boolean;
     nodeType?: string;
     sequence?: string;
     configuration?: any;
     streams?: Stream[];
     name?: string;
     path?: string[];
     depth?: number;
     sort?: number;
     children?: Node[];
     metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     error?: null | string;
     isDeleted?: boolean;
}

export interface Page {
     pageId?: string;
     sequence?: string;
     node?: Node;
     layoutId?: string;
     regions?: Region[];
     metaData?: any /* \Frontastic\UserBundle\Domain\MetaData */;
     isDeleted?: boolean;
     state?: string;
     scheduledFromTimestamp?: null | number;
     scheduledToTimestamp?: null | number;
     nodesPagesOfTypeSortIndex?: null | number;
     scheduleCriterion?: string;
}

export interface Preview {
     previewId?: string;
     createdAt?: any /* \DateTime */;
     node?: Node;
     page?: Page;
     metaData?: any /* \FrontendBundle\UserBundle\Domain\MetaData */;
}

export interface ProjectConfiguration {
     projectConfigurationId?: string;
     configuration?: any;
     metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     sequence?: string;
     isDeleted?: boolean;
}

export interface Redirect {
     redirectId?: string;
     sequence?: string;
     path?: string;
     query?: string;
     targetType?: string;
     target?: string;
     language?: null | string;
     metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     isDeleted?: boolean;
}

export interface Region {
     regionId?: string;
     configuration?: FrontendRegionConfiguration;
     elements?: Cell[];
     cells?: Cell[];
}

export interface Route {
     nodeId?: string;
     route?: string;
     locale?: null | string;
}

export interface Schema {
     schemaId?: string;
     schemaType?: string;
     schema?: any;
     metaData?: any /* \Frontastic\Backstage\UserBundle\Domain\MetaData */;
     sequence?: string;
     isDeleted?: boolean;
}

export interface Stream {
     streamId?: string;
     type?: string;
     name?: string;
     configuration?: any;
     tastics?: Tastic[];
}

export interface StreamContext {
     node?: Node;
     page?: Page;
     context?: ApiCoreContext;
     usingTastics?: Tastic[];
     parameters?: any;
}

export interface Tastic {
     tasticId?: string;
     tasticType?: string;
     configuration?: FrontendTasticConfiguration;
}

export interface ViewData {
     stream?: any;
     tastic?: any;
}
