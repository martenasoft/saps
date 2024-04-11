import {FieldGuesser, ShowGuesser} from "@api-platform/admin";

const FeedbackShow = (props:{props:any}) => (
  <ShowGuesser {...props}>
    <FieldGuesser source={"fromEmail"} />
    <FieldGuesser source={"subject"} />
    <FieldGuesser source={"text"} />
    <FieldGuesser source={"status"} />
    <FieldGuesser source={"createdAt"} />
    <FieldGuesser source={"updatedAt"} />
    <FieldGuesser source={"defaultStatus"} />
  </ShowGuesser>
);
export default FeedbackShow;
