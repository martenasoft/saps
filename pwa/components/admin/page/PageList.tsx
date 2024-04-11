import {FieldGuesser, ListGuesser} from "@api-platform/admin";
import MenuShow from "../menu/MenuShow";

const PageList = (props:{props:any}) => (



<ListGuesser {...props}>
  <hr />

    <FieldGuesser source={"name"} />

    <FieldGuesser source={"publicAt"} />
    <FieldGuesser source={"image"} />
    <FieldGuesser source={"isPreviewOnMain"} />
    <FieldGuesser source={"status"} />
    <FieldGuesser source={"slug"} />
    <FieldGuesser source={"menu"} />
    <FieldGuesser source={"type"} />
  </ListGuesser>
);

export default PageList;
