import {EditGuesser, InputGuesser, ResourceGuesser} from "@api-platform/admin";
const PageEdit = (props:{props:any})  => {

return (<EditGuesser {...props}>

    <InputGuesser source={"name"} />
    <InputGuesser source={"preview"} />
    <InputGuesser source={"body"} />
    <InputGuesser source={"position"} />
    <InputGuesser source={"publicAt"} />
    <InputGuesser source={"image"} />
    <InputGuesser source={"isPreviewOnMain"} />
    <InputGuesser source={"seoTitle"} />
    <InputGuesser source={"seoDescription"} />
    <InputGuesser source={"seoKeywords"} />
    <InputGuesser source={"ogTitle"} />
    <InputGuesser source={"ogDescription"} />
    <InputGuesser source={"ogUrl"} />
    <InputGuesser source={"ogImage"} />
    <InputGuesser source={"ogType"} />
    <InputGuesser source={"status"} />
    <InputGuesser source={"slug"} />
    <InputGuesser source={"menu"} />
    <InputGuesser source={"type"} />
  </EditGuesser>
)};

export default PageEdit;
