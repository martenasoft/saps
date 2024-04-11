import {FieldGuesser, ListGuesser} from "@api-platform/admin";

const FeedbackList = (props: { props: any }) => {
  return (
    <ListGuesser {...props}>
      <FieldGuesser source={"fromEmail"}/>
      <FieldGuesser source={"subject"}/>
      <FieldGuesser source={"text"}/>
      <FieldGuesser source={"status"}/>
      <FieldGuesser source={"createdAt"}/>
      <FieldGuesser source={"updatedAt"}/>
      <FieldGuesser source={"defaultStatus"}/>
    </ListGuesser>
  )
};
export default FeedbackList;
