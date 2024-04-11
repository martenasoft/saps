import {FieldGuesser, ShowGuesser} from "@api-platform/admin";
import PageEdit from "./PageEdit";

const PageShow = (props:{props:any}) => (
  <ShowGuesser {...props}>
    <FieldGuesser source={"name"} />
    <FieldGuesser source={"preview"} />
    <FieldGuesser source={"body"} />
    <FieldGuesser source={"position"} />
    <FieldGuesser source={"publicAt"} />
    <FieldGuesser source={"image"} />
    <FieldGuesser source={"isPreviewOnMain"} />
    <FieldGuesser source={"seoTitle"} />
    <FieldGuesser source={"seoDescription"} />
    <FieldGuesser source={"seoKeywords"} />
    <FieldGuesser source={"ogTitle"} />
    <FieldGuesser source={"ogDescription"} />
    <FieldGuesser source={"ogUrl"} />
    <FieldGuesser source={"ogImage"} />
    <FieldGuesser source={"ogType"} />
    <FieldGuesser source={"status"} />
    <FieldGuesser source={"slug"} />
    <FieldGuesser source={"menu"} />
    <FieldGuesser source={"type"} />
  </ShowGuesser>
);

export default PageShow;
